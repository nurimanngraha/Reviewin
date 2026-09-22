<?php

namespace Database\Seeders;

use App\Models\Activation;
use App\Models\Business;
use App\Models\Device;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Administrator User
        $admin = User::firstOrCreate(
            ['email' => 'admin@reviewin.test'],
            [
                'name' => 'Administrator ReviewIn',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081199887766',
            ]
        );

        // 2. Create Business Owner User
        $owner = User::firstOrCreate(
            ['email' => 'owner@reviewin.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'business_owner',
                'phone' => '081234567890',
            ]
        );

        // 3. Create Businesses for the Owner
        $business1 = Business::firstOrCreate(
            ['slug' => 'kopi-kenangan-senopati'],
            [
                'user_id' => $owner->id,
                'name' => 'Kopi Kenangan Senopati',
                'category' => 'Coffee Shop & Bakery',
                'address' => 'Jl. Senopati No. 42, Kebayoran Baru, Jakarta Selatan',
                'phone' => '021-5551234',
                'email' => 'senopati@kopikenangan.test',
                'google_review_url' => 'https://search.google.com/local/writereview?placeid=ChIJN1t_tDeuEmsRUsoyG83frY4',
                'is_active' => true,
            ]
        );

        $business2 = Business::firstOrCreate(
            ['slug' => 'warung-steak-shake-bintaro'],
            [
                'user_id' => $owner->id,
                'name' => 'Warung Steak & Shake Bintaro',
                'category' => 'Restoran Steak',
                'address' => 'Sektor 7 Bintaro Jaya, Tangerang Selatan',
                'phone' => '021-7778899',
                'email' => 'bintaro@warungsteak.test',
                'google_review_url' => 'https://search.google.com/local/writereview?placeid=ChIJbe_tDeuEmsRUsoyG83frY5',
                'is_active' => true,
            ]
        );

        // 4. Create Devices in Different States

        // Device 1: Active, linked to Business 1 (Demo Active QR / NFC)
        $devActive1 = Device::updateOrCreate(
            ['device_code' => 'REV-DEMO01'],
            [
                'name' => 'Meja Kasir Utama',
                'type' => 'qr_nfc',
                'business_id' => $business1->id,
                'status' => 'active',
                'total_scans' => 74,
                'total_qr_scans' => 48,
                'total_nfc_scans' => 26,
                'activated_at' => Carbon::now()->subDays(12),
                'last_scanned_at' => Carbon::now()->subMinutes(15),
                'notes' => 'Stand akrilik di depan kasir pembayaran.',
            ]
        );

        // Record Activation for Device 1
        Activation::firstOrCreate(
            ['device_id' => $devActive1->id],
            [
                'business_id' => $business1->id,
                'user_id' => $owner->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
                'notes' => 'Aktivasi mandiri perdana oleh pemilik bisnis.',
                'activated_at' => Carbon::now()->subDays(12),
            ]
        );

        // Device 2: Unactivated (Ready for testing first scan activation flow)
        Device::updateOrCreate(
            ['device_code' => 'REV-DEMO02'],
            [
                'name' => 'Stand Meja Baru #1',
                'type' => 'qr_nfc',
                'business_id' => null,
                'status' => 'unactivated',
                'total_scans' => 0,
                'total_qr_scans' => 0,
                'total_nfc_scans' => 0,
                'notes' => 'Perangkat fisik baru dari admin, siap diaktivasi pemilik bisnis.',
            ]
        );

        // Device 3: Inactive
        Device::updateOrCreate(
            ['device_code' => 'REV-DEMO03'],
            [
                'name' => 'Stand Meja Cadangan',
                'type' => 'qr_nfc',
                'business_id' => $business1->id,
                'status' => 'inactive',
                'total_scans' => 12,
                'total_qr_scans' => 8,
                'total_nfc_scans' => 4,
                'activated_at' => Carbon::now()->subDays(20),
                'last_scanned_at' => Carbon::now()->subDays(5),
                'notes' => 'Dinonaktifkan sementara karena renovasi meja outdoor.',
            ]
        );

        // Device 4: Active, linked to Business 2
        $devActive2 = Device::updateOrCreate(
            ['device_code' => 'REV-DEMO04'],
            [
                'name' => 'Sticker Meja VIP 01',
                'type' => 'qr_nfc',
                'business_id' => $business2->id,
                'status' => 'active',
                'total_scans' => 38,
                'total_qr_scans' => 24,
                'total_nfc_scans' => 14,
                'activated_at' => Carbon::now()->subDays(8),
                'last_scanned_at' => Carbon::now()->subHours(2),
                'notes' => 'Sticker NFC anti-air di meja VIP.',
            ]
        );

        Activation::firstOrCreate(
            ['device_id' => $devActive2->id],
            [
                'business_id' => $business2->id,
                'user_id' => $owner->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Linux; Android 14; Pixel 8)',
                'notes' => 'Aktivasi mandiri oleh pemilik.',
                'activated_at' => Carbon::now()->subDays(8),
            ]
        );

        // Device 5: Blocked
        Device::updateOrCreate(
            ['device_code' => 'REV-DEMO05'],
            [
                'name' => 'Kartu NFC Kasir Lama',
                'type' => 'nfc_only',
                'business_id' => $business1->id,
                'status' => 'blocked',
                'total_scans' => 5,
                'notes' => 'Diblokir admin karena dilaporkan hilang.',
            ]
        );

        // Device 6: Unactivated Stand 2
        Device::updateOrCreate(
            ['device_code' => 'REV-DEMO06'],
            [
                'name' => 'Stand Akrilik Pintu Keluar',
                'type' => 'qr_nfc',
                'business_id' => null,
                'status' => 'unactivated',
            ]
        );

        // 5. Seed Realistic Telemetry Scan Logs
        $platforms = [
            ['platform' => 'iOS', 'browser' => 'Safari', 'device_type' => 'Mobile', 'ua' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1'],
            ['platform' => 'Android', 'browser' => 'Chrome', 'device_type' => 'Mobile', 'ua' => 'Mozilla/5.0 (Linux; Android 14; SM-S928B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36'],
            ['platform' => 'Android', 'browser' => 'Chrome', 'device_type' => 'Mobile', 'ua' => 'Mozilla/5.0 (Linux; Android 13; Xiaomi 13) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Mobile Safari/537.36'],
            ['platform' => 'iOS', 'browser' => 'Safari', 'device_type' => 'Tablet', 'ua' => 'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1'],
        ];

        // Seed logs across last 14 days
        for ($day = 13; $day >= 0; $day--) {
            $scanCount = rand(3, 8);
            for ($k = 0; $k < $scanCount; $k++) {
                $targetDev = ($k % 3 === 0) ? $devActive2 : $devActive1;
                $scanType = ($k % 3 === 0) ? 'nfc' : 'qr';
                $client = $platforms[array_rand($platforms)];
                $time = Carbon::now()->subDays($day)->setHour(rand(9, 21))->setMinute(rand(0, 59))->setSecond(rand(0, 59));

                Scan::create([
                    'device_id' => $targetDev->id,
                    'business_id' => $targetDev->business_id,
                    'scan_type' => $scanType,
                    'ip_address' => '114.124.' . rand(1, 250) . '.' . rand(1, 250),
                    'user_agent' => $client['ua'],
                    'device_type' => $client['device_type'],
                    'platform' => $client['platform'],
                    'browser' => $client['browser'],
                    'scanned_at' => $time,
                ]);
            }
        }
    }
}
