<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanTrackingService
{
    /**
     * Record a scan event for the given device
     */
    public function recordScan(Device $device, Request $request): Scan
    {
        $userAgent = $request->userAgent() ?? '';
        $scanType = $this->detectScanType($request);
        $deviceType = $this->detectDeviceType($userAgent);
        $platform = $this->detectPlatform($userAgent);
        $browser = $this->detectBrowser($userAgent);
        $ip = $request->ip();

        return DB::transaction(function () use ($device, $scanType, $deviceType, $platform, $browser, $ip, $userAgent) {
            $scan = Scan::create([
                'device_id' => $device->id,
                'business_id' => $device->business_id,
                'scan_type' => $scanType,
                'ip_address' => $ip,
                'user_agent' => substr($userAgent, 0, 500),
                'device_type' => $deviceType,
                'platform' => $platform,
                'browser' => $browser,
                'scanned_at' => now(),
            ]);

            // Increment atomic counters on device
            $updateData = [
                'total_scans' => DB::raw('total_scans + 1'),
                'last_scanned_at' => now(),
            ];

            if ($scanType === 'nfc') {
                $updateData['total_nfc_scans'] = DB::raw('total_nfc_scans + 1');
            } else {
                $updateData['total_qr_scans'] = DB::raw('total_qr_scans + 1');
            }

            Device::where('id', $device->id)->update($updateData);

            return $scan;
        });
    }

    /**
     * Detect if scan is NFC or QR based on query params or headers
     */
    public function detectScanType(Request $request): string
    {
        $type = strtolower($request->query('t', $request->query('type', 'qr')));
        return in_array($type, ['nfc', 'tag', 'tap']) ? 'nfc' : 'qr';
    }

    /**
     * Detect mobile, tablet, or desktop
     */
    public function detectDeviceType(string $ua): string
    {
        if (preg_match('/(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk)/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/(mobi|iphone|ipod|phone|blackberry|opera mini|iemobile|mobile)/i', $ua)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    /**
     * Detect operating system / platform
     */
    public function detectPlatform(string $ua): string
    {
        if (preg_match('/iphone|ipad|ipod/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }
        if (preg_match('/windows nt/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'macOS';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }
        return 'Other';
    }

    /**
     * Detect browser
     */
    public function detectBrowser(string $ua): string
    {
        if (preg_match('/edg\//i', $ua)) {
            return 'Edge';
        }
        if (preg_match('/chrome\//i', $ua) && !preg_match('/edg\//i', $ua)) {
            return 'Chrome';
        }
        if (preg_match('/safari\//i', $ua) && !preg_match('/chrome\//i', $ua)) {
            return 'Safari';
        }
        if (preg_match('/firefox\//i', $ua)) {
            return 'Firefox';
        }
        if (preg_match('/opera|opr\//i', $ua)) {
            return 'Opera';
        }
        return 'Browser';
    }
}
