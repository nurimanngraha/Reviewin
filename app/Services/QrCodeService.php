<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Http\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate inline SVG string of the QR Code
     */
    public function generateSvg(string $url, int $size = 250): string
    {
        return QrCode::size($size)
            ->format('svg')
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url);
    }

    /**
     * Generate raw SVG download response
     */
    public function downloadSvg(Device $device): Response
    {
        $url = $device->qr_url;
        $svg = $this->generateSvg($url, 600);
        $filename = 'QR-' . $device->device_code . '.svg';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate raw PNG download response using GD / BaconQrCode or SVG fallback
     */
    public function downloadPng(Device $device): Response
    {
        $url = $device->qr_url;
        $filename = 'QR-' . $device->device_code . '.png';

        try {
            if (extension_loaded('gd') || extension_loaded('imagick')) {
                $png = QrCode::format('png')
                    ->size(800)
                    ->errorCorrection('H')
                    ->margin(2)
                    ->generate($url);

                return response($png, 200, [
                    'Content-Type' => 'image/png',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            }
        } catch (\Throwable $e) {
            // If PNG driver fails on specific environment, fall back to SVG with clear name
        }

        // Fallback to SVG if PNG image driver is not active
        return $this->downloadSvg($device);
    }
}
