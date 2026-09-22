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
    public function show(string $code, Request $request): \Illuminate\Http\Response|View|RedirectResponse
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

        // Device is unactivated -> Enforce Business Owner Login
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => '/activate/' . $code])
                ->with('warning', 'Silakan masuk / login terlebih dahulu sebagai Pemilik Bisnis untuk mengaktifkan kartu QR & NFC ini.');
        }

        $user = Auth::user();
        $userBusinesses = $user->businesses()->get();

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
                ->with('error', 'Perangkat ini sudah aktif atau tidak dalam status unactivated.');
        }

        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => '/activate/' . $code])
                ->with('warning', 'Sesi Anda telah berakhir. Silakan login kembali untuk menyelesaikan aktivasi.');
        }

        $user = Auth::user();

        $rules = [
            'activation_code' => ['required', 'string', 'max:50'],
            'business_id' => ['nullable', 'exists:businesses,id'],
            'device_label' => ['nullable', 'string', 'max:100'],
            'google_place_id' => ['required', 'string', 'min:5', 'max:150'],
        ];

        // If new business
        if (!$request->filled('business_id')) {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $rules['category'] = ['nullable', 'string', 'max:100'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
            $rules['phone'] = ['nullable', 'string', 'max:30'];
        }

        $messages = [
            'activation_code.required' => 'Kode Kartu (Activation Code) wajib dimasukkan untuk verifikasi perangkat.',
            'google_place_id.required' => 'Google Place ID wajib diisi untuk menghubungkan lokasi Google Review bisnis Anda.',
            'business_name.required' => 'Nama bisnis wajib diisi jika Anda mendaftarkan bisnis baru.',
        ];

        $validated = $request->validate($rules, $messages);

        // Verify activation code against device record
        if (!empty($device->activation_code)) {
            $inputCode = strtoupper(trim($validated['activation_code']));
            $expectedCode = strtoupper(trim($device->activation_code));

            if ($inputCode !== $expectedCode) {
                return back()->withInput()->withErrors([
                    'activation_code' => 'Kode Kartu (Activation Code) yang Anda masukkan tidak sesuai dengan perangkat ini.',
                ])->with('error', 'Validasi gagal: Kode Kartu tidak cocok.');
            }
        }

        try {
            $activatedDevice = $this->activationService->activate($device, $user, $validated, $request);

            return redirect()->route('device.activated.success', ['code' => $activatedDevice->device_code])
                ->with('success', 'Selamat! Kartu QR & NFC berhasil diaktivasi dan sekarang terhubung dengan bisnis Anda.');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['activation_code' => $e->getMessage()]);
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
