<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Device;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeviceWorkflowTest extends TestCase
{
    protected function getOrCreateUnactivatedDevice(): Device
    {
        return Device::firstOrCreate(
            ['status' => 'unactivated'],
            [
                'device_code' => 'TEST-' . strtoupper(Str::random(6)),
                'name' => 'Test Stand Meja',
                'type' => 'qr_nfc',
                'status' => 'unactivated',
            ]
        );
    }

    public function test_unactivated_device_redirects_to_activation_page(): void
    {
        $device = $this->getOrCreateUnactivatedDevice();

        $response = $this->get('/r/' . $device->device_code);
        $response->assertRedirect(route('device.activate', ['code' => $device->device_code]));
    }

    public function test_active_device_records_telemetry_and_redirects_to_google_review(): void
    {
        $device = Device::where('status', 'active')->first();
        $this->assertNotNull($device, 'Active device must exist');

        $initialTotalScans = $device->total_scans;
        $initialQrScans = $device->total_qr_scans;

        // Customer scans QR
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
        ])->get('/r/' . $device->device_code . '?t=qr');

        // Must redirect 302 directly to Google Review
        $response->assertStatus(302);
        $response->assertRedirect($device->effective_review_url);

        // Check telemetry record in database
        $latestScan = Scan::where('device_id', $device->id)->latest('id')->first();
        $this->assertNotNull($latestScan);
        $this->assertEquals('qr', $latestScan->scan_type);
        $this->assertEquals('Mobile', $latestScan->device_type);
        $this->assertEquals('iOS', $latestScan->platform);

        // Check device counter increment
        $device->refresh();
        $this->assertEquals($initialTotalScans + 1, $device->total_scans);
        $this->assertEquals($initialQrScans + 1, $device->total_qr_scans);
    }

    public function test_active_device_nfc_tap_records_nfc_telemetry(): void
    {
        $device = Device::where('status', 'active')->first();
        $initialNfcScans = $device->total_nfc_scans;

        // Customer taps NFC card
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Linux; Android 14; Pixel 8)',
        ])->get('/r/' . $device->device_code . '?t=nfc');

        $response->assertStatus(302);
        $response->assertRedirect($device->effective_review_url);

        $latestScan = Scan::where('device_id', $device->id)->latest('id')->first();
        $this->assertEquals('nfc', $latestScan->scan_type);
        $this->assertEquals('Android', $latestScan->platform);

        $device->refresh();
        $this->assertEquals($initialNfcScans + 1, $device->total_nfc_scans);
    }

    public function test_inactive_and_blocked_devices_show_notice_page(): void
    {
        $inactiveDevice = Device::where('status', 'inactive')->first();
        $response = $this->get('/r/' . $inactiveDevice->device_code);
        $response->assertStatus(403);
        $response->assertSee('Perangkat Tidak Dapat Digunakan');

        $blockedDevice = Device::where('status', 'blocked')->first();
        $response2 = $this->get('/r/' . $blockedDevice->device_code);
        $response2->assertStatus(403);
        $response2->assertSee('Perangkat Tidak Dapat Digunakan');
    }

    public function test_unknown_device_shows_404_page(): void
    {
        $response = $this->get('/r/REV-UNKNOWN9999');
        $response->assertStatus(404);
        $response->assertSee('Perangkat Tidak Terdaftar');
    }

    public function test_business_owner_can_activate_unactivated_device(): void
    {
        $owner = User::where('role', 'business_owner')->first();
        $activationCode = 'ACT-998877';

        $device = Device::create([
            'device_code' => 'TEST-ACT-' . strtoupper(Str::random(6)),
            'activation_code' => $activationCode,
            'name' => 'Meja Baru Belum Aktif',
            'type' => 'qr_nfc',
            'status' => 'unactivated',
        ]);

        // Guest accessing unactivated device activation page gets redirected to login
        $guestAccess = $this->get('/activate/' . $device->device_code);
        $guestAccess->assertRedirect(route('login', ['redirect' => '/activate/' . $device->device_code]));

        // Attempt activation with WRONG activation code -> should fail
        $wrongCodeResponse = $this->actingAs($owner)->post('/activate/' . $device->device_code, [
            'activation_code' => 'ACT-WRONG-CODE',
            'business_name' => 'Kedai Kopi Mantap',
            'google_place_id' => 'ChIJN1t_tDeuEmsRUsoyG83frY4',
        ]);
        $wrongCodeResponse->assertSessionHasErrors('activation_code');
        $device->refresh();
        $this->assertEquals('unactivated', $device->status);

        // Attempt activation with CORRECT activation code and Google Place ID
        $postData = [
            'activation_code' => $activationCode,
            'business_name' => 'Kedai Kopi Mantap ' . Str::random(4),
            'google_place_id' => 'ChIJN1t_tDeuEmsRUsoyG83frY4',
            'device_label' => 'Meja Kasir Tambahan',
        ];

        $response = $this->actingAs($owner)->post('/activate/' . $device->device_code, $postData);

        $response->assertRedirect(route('device.activated.success', ['code' => $device->device_code]));

        $device->refresh();
        $this->assertEquals('active', $device->status);
        $this->assertNotNull($device->business_id);
        $this->assertNotNull($device->activated_at);
        $this->assertEquals('ChIJN1t_tDeuEmsRUsoyG83frY4', $device->business->google_place_id);

        // Subsequent customer scan goes straight to Google Review with Place ID without login
        $expectedReviewUrl = 'https://search.google.com/local/writereview?placeid=ChIJN1t_tDeuEmsRUsoyG83frY4';
        $customerScan = $this->get('/r/' . $device->device_code);
        $customerScan->assertStatus(302);
        $customerScan->assertRedirect($expectedReviewUrl);
    }

    public function test_role_based_access_control(): void
    {
        $admin = User::where('role', 'admin')->first();
        $owner = User::where('role', 'business_owner')->first();

        // Guest access redirects to login
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/portal/dashboard')->assertRedirect(route('login'));

        // Owner trying to access admin gets 403
        $this->actingAs($owner)->get('/admin/dashboard')->assertStatus(403);

        // Admin can access admin dashboard
        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);

        // Owner can access portal dashboard
        $this->actingAs($owner)->get('/portal/dashboard')->assertStatus(200);
    }

    public function test_admin_can_download_qr_svg_and_png(): void
    {
        $admin = User::where('role', 'admin')->first();
        $device = Device::first();

        // Download SVG
        $svgResponse = $this->actingAs($admin)->get("/admin/devices/{$device->id}/download/svg");
        $svgResponse->assertStatus(200);
        $svgResponse->assertHeader('Content-Type', 'image/svg+xml');
        $this->assertStringContainsString('<svg', $svgResponse->getContent());

        // Download PNG
        $pngResponse = $this->actingAs($admin)->get("/admin/devices/{$device->id}/download/png");
        $pngResponse->assertStatus(200);
    }

    public function test_admin_can_bulk_create_devices(): void
    {
        $admin = User::where('role', 'admin')->first();
        $initialCount = Device::count();
        $uniquePrefix = 'B' . strtoupper(substr(uniqid(), -4));

        $response = $this->actingAs($admin)->post('/admin/devices', [
            'is_bulk' => 1,
            'count' => 5,
            'prefix' => $uniquePrefix,
            'type' => 'qr_nfc',
            'device_name_prefix' => 'Batch Test',
        ]);

        $response->assertRedirect(route('admin.devices.index'));
        $this->assertEquals($initialCount + 5, Device::count());

        $bulkDevices = Device::where('device_code', 'like', $uniquePrefix . '-%')->get();
        $this->assertCount(5, $bulkDevices);
        foreach ($bulkDevices as $bDev) {
            $this->assertEquals('unactivated', $bDev->status);
        }
    }

    public function test_admin_can_reset_active_device(): void
    {
        $admin = User::where('role', 'admin')->first();
        $device = Device::where('status', 'active')->first();

        $response = $this->actingAs($admin)->post("/admin/devices/{$device->id}/reset", [
            'reason' => 'Uji coba reset admin',
        ]);

        $response->assertRedirect();
        $device->refresh();
        $this->assertEquals('unactivated', $device->status);
        $this->assertNull($device->business_id);
    }
}
