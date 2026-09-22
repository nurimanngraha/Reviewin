<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\DeviceActivationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivationController extends Controller
{
    public function __construct(
        protected DeviceActivationService $activationService
    ) {}

    /**
     * Show device activation page
     */
    public function show(string $code, Request $request): View|RedirectResponse
    {
        $device = Device::with('business')->where('device_code', $code)->first();

        if (!$device) {
            return response()->view('errors.device_not_found', [
                'device_code' => $code,
            ], 404);
        }

        // If already active, inform user
        if ($device->status === 'active') {
            return view('devices.already_active', [
                'device' => $device,
            ]);
        }

        // If inactive or blocked
        if (in_array($device->status, ['inactive', 'blocked'])) {
            return response()->view('errors.device_inactive', [
                'device' => $device,
                'status' => $device->status,
            ], 403);
        }

        // Device is unactivated -> Activation form
        $user = Auth::user();
        $userBusinesses = $user ? $user->businesses()->get() : collect();

        return view('devices.activate', [
            'device' => $device,
            'user' => $user,
            'userBusinesses' => $userBusinesses,
        ]);
    }

    /**
     * Process device activation submitted by business owner
     */
    public function process(string $code, Request $request): RedirectResponse
    {
        $device = Device::where('device_code', $code)->first();

        if (!$device) {
            return back()->with('error', 'Perangkat tidak ditemukan.');
        }

        if ($device->status !== 'unactivated') {
            return redirect()->route('device.activate', ['code' => $code])
                ->with('error', 'Perangkat ini sudah tidak dalam status unactivated.');
        }

        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => '/activate/' . $code])
                ->with('warning', 'Silakan login terlebih dahulu untuk mengaktifkan perangkat ini.');
        }

        $user = Auth::user();

        $rules = [
            'business_id' => ['nullable', 'exists:businesses,id'],
            'device_label' => ['nullable', 'string', 'max:100'],
            'google_review_url' => ['required', 'url', 'max:1000'],
        ];

        // If new business
        if (!$request->filled('business_id')) {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $rules['category'] = ['nullable', 'string', 'max:100'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
            $rules['phone'] = ['nullable', 'string', 'max:30'];
        }

        $messages = [
            'google_review_url.required' => 'Link Google Review wajib diisi agar pelanggan dapat diarahkan ke halaman review.',
            'google_review_url.url' => 'Format Link Google Review tidak valid. Pastikan diawali dengan http:// atau https://.',
            'business_name.required' => 'Nama bisnis wajib diisi jika mendaftarkan bisnis baru.',
        ];

        $validated = $request->validate($rules, $messages);

        try {
            $activatedDevice = $this->activationService->activate($device, $user, $validated, $request);

            return redirect()->route('device.activated.success', ['code' => $activatedDevice->device_code])
                ->with('success', 'Selamat! Perangkat berhasil diaktivasi dan siap digunakan.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal mengaktifkan perangkat: ' . $e->getMessage());
        }
    }

    /**
     * Activation success screen
     */
    public function success(string $code): View
    {
        $device = Device::with('business')->where('device_code', $code)->firstOrFail();

        return view('devices.activated_success', [
            'device' => $device,
        ]);
    }
}
