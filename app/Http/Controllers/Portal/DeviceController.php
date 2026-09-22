<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DeviceController extends Controller
{
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
     * Show physical card details and usage telemetry
     */
    public function show(Device $device): View
    {
        $this->authorizeDevice($device);

        $device->load(['business', 'scans' => function ($q) {
            $q->latest('scanned_at')->take(15);
        }]);

        return view('portal.devices.show', [
            'device' => $device,
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
