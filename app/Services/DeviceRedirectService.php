<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DeviceRedirectService
{
    public function __construct(
        protected ScanTrackingService $scanTrackingService
    ) {}

    /**
     * Resolve incoming scan/tap at /r/{device_code}
     */
    public function handle(string $deviceCode, Request $request): RedirectResponse|Response|View
    {
        $device = Device::with('business')->where('device_code', $deviceCode)->first();

        // 1. Device Not Found
        if (!$device) {
            return response()->view('errors.device_not_found', [
                'device_code' => $deviceCode,
            ], 404);
        }

        // 2. Unactivated Device -> First scan/tap workflow -> Redirect to Activation page
        if ($device->status === 'unactivated') {
            return redirect()->route('device.activate', ['code' => $device->device_code]);
        }

        // 3. Inactive or Blocked Device -> Show dedicated warning page
        if (in_array($device->status, ['inactive', 'blocked'])) {
            return response()->view('errors.device_inactive', [
                'device' => $device,
                'status' => $device->status,
            ], 403);
        }

        // 4. Active Device -> Record Scan and Fast Redirect to Google Review
        if ($device->status === 'active') {
            // Record scan telemetry asynchronously or inline
            $this->scanTrackingService->recordScan($device, $request);

            $reviewUrl = $device->effective_review_url;

            // In case the business doesn't have a review link set yet
            if (empty($reviewUrl)) {
                return response()->view('devices.missing_review_link', [
                    'device' => $device,
                ]);
            }

            // Clean 302 redirect directly to Google Review
            return redirect()->away($reviewUrl, 302, [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return response()->view('errors.device_inactive', [
            'device' => $device,
            'status' => $device->status,
        ], 403);
    }
}
