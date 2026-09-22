<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Device;
use App\Services\DeviceActivationService;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService,
        protected DeviceActivationService $activationService
    ) {}

    /**
     * Display device list with filters
     */
    public function index(Request $request): View
    {
        $query = Device::with('business');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter business
        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }

        // Search code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('device_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $devices = $query->latest()->paginate(15)->withQueryString();
        $businesses = Business::orderBy('name')->get();

        $counts = [
            'all' => Device::count(),
            'unactivated' => Device::unactivated()->count(),
            'active' => Device::active()->count(),
            'inactive' => Device::inactive()->count(),
            'blocked' => Device::blocked()->count(),
        ];

        return view('admin.devices.index', [
            'devices' => $devices,
            'businesses' => $businesses,
            'counts' => $counts,
            'filters' => $request->only(['status', 'business_id', 'search']),
        ]);
    }

    /**
     * Show create device form
     */
    public function create(): View
    {
        $businesses = Business::orderBy('name')->get();

        return view('admin.devices.create', [
            'businesses' => $businesses,
        ]);
    }

    /**
     * Store single or bulk devices
     */
    public function store(Request $request): RedirectResponse
    {
        $isBulk = $request->boolean('is_bulk');

        if ($isBulk) {
            $request->validate([
                'count' => ['required', 'integer', 'min:1', 'max:100'],
                'prefix' => ['nullable', 'string', 'max:10', 'regex:/^[A-Za-z0-9_-]+$/'],
                'type' => ['required', 'in:qr_nfc,qr_only,nfc_only'],
                'device_name_prefix' => ['nullable', 'string', 'max:50'],
                'notes' => ['nullable', 'string', 'max:500'],
            ], [
                'count.required' => 'Jumlah device wajib diisi.',
                'count.max' => 'Maksimal 100 device per pembuatan massal.',
            ]);

            $count = (int) $request->input('count');
            $prefix = strtoupper($request->input('prefix', 'REV'));
            $namePrefix = $request->input('device_name_prefix', 'Review Device');
            $created = 0;

            for ($i = 0; $i < $count; $i++) {
                $uniqueCode = $this->generateUniqueCode($prefix);
                $activationCode = Device::generateActivationCode();

                Device::create([
                    'device_code' => $uniqueCode,
                    'activation_code' => $activationCode,
                    'name' => $namePrefix . ' #' . ($i + 1),
                    'type' => $request->input('type', 'qr_nfc'),
                    'status' => 'unactivated',
                    'notes' => $request->input('notes'),
                ]);
                $created++;
            }

            return redirect()->route('admin.devices.index')
                ->with('success', "Berhasil membuat {$created} perangkat baru dengan status unactivated dan Kode Kartu unik!");
        }

        // Single device creation
        $validated = $request->validate([
            'device_code' => ['nullable', 'string', 'max:30', 'unique:devices,device_code'],
            'activation_code' => ['nullable', 'string', 'max:30', 'unique:devices,activation_code'],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:qr_nfc,qr_only,nfc_only'],
            'business_id' => ['nullable', 'exists:businesses,id'],
            'status' => ['required', 'in:unactivated,active,inactive,blocked'],
            'google_review_url_override' => ['nullable', 'url', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama label perangkat wajib diisi.',
            'device_code.unique' => 'Kode perangkat ini sudah digunakan.',
            'activation_code.unique' => 'Kode aktivasi ini sudah digunakan.',
        ]);

        $code = !empty($validated['device_code'])
            ? strtoupper($validated['device_code'])
            : $this->generateUniqueCode();

        $activationCode = !empty($validated['activation_code'])
            ? strtoupper($validated['activation_code'])
            : Device::generateActivationCode();

        $device = Device::create([
            'device_code' => $code,
            'activation_code' => $activationCode,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'business_id' => $validated['business_id'] ?? null,
            'status' => $validated['status'],
            'google_review_url_override' => $validated['google_review_url_override'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'activated_at' => ($validated['status'] === 'active' && !empty($validated['business_id'])) ? now() : null,
        ]);

        return redirect()->route('admin.devices.show', $device)
            ->with('success', "Perangkat [{$device->device_code}] berhasil ditambahkan dengan Kode Kartu [{$device->activation_code}]!");
    }

    /**
     * Show device details, QR preview, NFC URL, and logs
     */
    public function show(Device $device): View
    {
        $device->load(['business.user', 'activations.user', 'scans' => function ($q) {
            $q->latest('scanned_at')->take(20);
        }]);

        $qrSvg = $this->qrCodeService->generateSvg($device->qr_url, 260);

        return view('admin.devices.show', [
            'device' => $device,
            'qrSvg' => $qrSvg,
        ]);
    }

    /**
     * Edit device form
     */
    public function edit(Device $device): View
    {
        $businesses = Business::orderBy('name')->get();

        return view('admin.devices.edit', [
            'device' => $device,
            'businesses' => $businesses,
        ]);
    }

    /**
     * Update device
     */
    public function update(Request $request, Device $device): RedirectResponse
    {
        $validated = $request->validate([
            'activation_code' => ['nullable', 'string', 'max:30', 'unique:devices,activation_code,' . $device->id],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:qr_nfc,qr_only,nfc_only'],
            'business_id' => ['nullable', 'exists:businesses,id'],
            'status' => ['required', 'in:unactivated,active,inactive,blocked'],
            'google_review_url_override' => ['nullable', 'url', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (!empty($validated['activation_code'])) {
            $validated['activation_code'] = strtoupper($validated['activation_code']);
        }

        $device->update($validated);

        return redirect()->route('admin.devices.show', $device)
            ->with('success', 'Data perangkat berhasil diperbarui!');
    }

    /**
     * Quick status update
     */
    public function updateStatus(Request $request, Device $device): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:unactivated,active,inactive,blocked'],
        ]);

        $device->update(['status' => $request->status]);

        return back()->with('success', "Status perangkat [{$device->device_code}] diubah menjadi: {$device->status}.");
    }

    /**
     * Reset device back to unactivated
     */
    public function reset(Request $request, Device $device): RedirectResponse
    {
        $reason = $request->input('reason', 'Reset oleh Administrator');
        $this->activationService->resetDevice($device, $reason);

        return back()->with('success', "Perangkat [{$device->device_code}] berhasil direset ke status unactivated.");
    }

    /**
     * Download QR Code in SVG format
     */
    public function downloadSvg(Device $device): Response
    {
        return $this->qrCodeService->downloadSvg($device);
    }

    /**
     * Download QR Code in PNG format
     */
    public function downloadPng(Device $device): Response
    {
        return $this->qrCodeService->downloadPng($device);
    }

    /**
     * Delete device
     */
    public function destroy(Device $device): RedirectResponse
    {
        $code = $device->device_code;
        $device->delete();

        return redirect()->route('admin.devices.index')
            ->with('success', "Perangkat [{$code}] berhasil dihapus dari sistem.");
    }

    /**
     * Helper to generate unique code
     */
    protected function generateUniqueCode(string $prefix = 'REV'): string
    {
        do {
            $random = strtoupper(Str::random(6));
            $code = "{$prefix}-{$random}";
        } while (Device::where('device_code', $code)->exists());

        return $code;
    }
}
