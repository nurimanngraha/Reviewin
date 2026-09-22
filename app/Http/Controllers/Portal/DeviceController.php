<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * List devices belonging to the logged-in owner
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $businessIds = $user->businesses()->pluck('id');
        $devices = Device::with('business')
            ->whereIn('business_id', $businessIds)
            ->latest()
            ->paginate(12);

        return view('portal.devices.index', [
            'devices' => $devices,
        ]);
    }

    /**
     * Show device details, QR preview, NFC URL
     */
    public function show(Device $device): View
    {
        $this->authorizeDevice($device);

        $device->load(['business', 'scans' => function ($q) {
            $q->latest('scanned_at')->take(15);
        }]);

        $qrSvg = $this->qrCodeService->generateSvg($device->qr_url, 260);

        return view('portal.devices.show', [
            'device' => $device,
            'qrSvg' => $qrSvg,
        ]);
    }

    /**
     * Update device label / name
     */
    public function update(Request $request, Device $device): RedirectResponse
    {
        $this->authorizeDevice($device);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ], [
            'name.required' => 'Nama label perangkat wajib diisi.',
        ]);

        $device->update(['name' => $validated['name']]);

        return back()->with('success', 'Nama label perangkat berhasil diperbarui!');
    }

    /**
     * Download QR Code SVG
     */
    public function downloadSvg(Device $device): Response
    {
        $this->authorizeDevice($device);

        return $this->qrCodeService->downloadSvg($device);
    }

    /**
     * Download QR Code PNG
     */
    public function downloadPng(Device $device): Response
    {
        $this->authorizeDevice($device);

        return $this->qrCodeService->downloadPng($device);
    }

    /**
     * Helper to verify ownership
     */
    protected function authorizeDevice(Device $device): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $businessIds = $user->businesses()->pluck('id')->toArray();

        if (!in_array($device->business_id, $businessIds)) {
            abort(403, 'Anda tidak memiliki akses ke perangkat ini.');
        }
    }
}
