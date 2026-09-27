<?php

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\Admin\BusinessController as AdminBusinessController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeviceController as AdminDeviceController;
use App\Http\Controllers\Admin\ScanLogController as AdminScanLogController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Portal\AnalyticsController as PortalAnalyticsController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\DeviceController as PortalDeviceController;
use App\Http\Controllers\Portal\SettingsController as PortalSettingsController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Dynamic Routing
|--------------------------------------------------------------------------
*/

// Homepage & Demo landing
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Core dynamic redirect endpoint for QR Code scans and NFC taps
Route::get('/r/{code}', [RedirectController::class, 'handle'])->name('device.redirect');

// First-time device activation by Business Owner
Route::get('/activate/{code}', [ActivationController::class, 'show'])->name('device.activate');
Route::post('/activate/{code}', [ActivationController::class, 'process'])->name('device.activate.process');
Route::get('/activated/{code}/success', [ActivationController::class, 'success'])->name('device.activated.success');

// Web Installer & Migrator for Shared Hosting (InfinityFree, cPanel without SSH)
Route::get('/system/migrate', function () {
    $secret = request()->query('key');
    $expectedKey = substr(hash('sha256', config('app.key')), 0, 16);

    if (!$secret || $secret !== $expectedKey) {
        abort(403, 'Akses migrasi sistem ditolak. Kunci otentikasi salah.');
    }

    try {
        if (request()->query('fresh')) {
            \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
            $migrateOutput = \Illuminate\Support\Facades\Artisan::output();
            $seedOutput = 'Semua tabel berhasil di-reset dan data default di-seeding ulang.';
        } else {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            $seedOutput = \Illuminate\Support\Facades\Artisan::output();
        }

        return response('<div style="background:#0f172a;color:#f8fafc;padding:30px;font-family:sans-serif;min-height:100vh;">' .
            '<h1 style="color:#10b981;margin-bottom:10px;">✅ Migrasi & Seeding Berhasil!</h1>' .
            '<p style="color:#94a3b8;">Database InfinityFree telah selesai disiapkan secara otomatis.</p>' .
            '<hr style="border-color:#334155;margin:20px 0;">' .
            '<h3 style="color:#38bdf8;">Log Artisan Migrate:</h3>' .
            '<pre style="background:#1e293b;padding:15px;border-radius:8px;color:#a5b4fc;overflow-x:auto;">' . e($migrateOutput ?: 'Semua tabel sudah up-to-date.') . '</pre>' .
            '<h3 style="color:#38bdf8;margin-top:20px;">Log Artisan Seed:</h3>' .
            '<pre style="background:#1e293b;padding:15px;border-radius:8px;color:#a5b4fc;overflow-x:auto;">' . e($seedOutput ?: 'Seeding selesai.') . '</pre>' .
            '<div style="margin-top:25px;display:flex;gap:12px;flex-wrap:wrap;">' .
            '<a href="' . route('login') . '" style="display:inline-block;padding:12px 24px;background:#4f46e5;color:white;text-decoration:none;border-radius:10px;font-weight:bold;">Menuju Halaman Login &rarr;</a>' .
            '</div></div>');
    } catch (\Throwable $e) {
        $isTableExists = str_contains($e->getMessage(), 'already exists') || $e->getCode() == '42S01';

        if ($isTableExists) {
            return response('<div style="background:#0f172a;color:#f8fafc;padding:30px;font-family:sans-serif;min-height:100vh;">' .
                '<h1 style="color:#38bdf8;margin-bottom:10px;">ℹ️ Tabel Database Sudah Ada!</h1>' .
                '<p style="color:#94a3b8;">Tabel-tabel database Anda (termasuk tabel <code>users</code>) sudah berhasil dibuat di InfinityFree. Website Anda sudah siap digunakan!</p>' .
                '<div style="margin-top:25px;display:flex;gap:15px;flex-wrap:wrap;">' .
                '<a href="' . route('login') . '" style="display:inline-block;padding:12px 24px;background:#10b981;color:white;text-decoration:none;border-radius:10px;font-weight:bold;">🚀 Langsung Buka Halaman Login &rarr;</a>' .
                '<a href="' . url('/system/migrate?key=' . $secret . '&fresh=1') . '" onclick="return confirm(\'PERINGATAN: Ini akan menghapus semua tabel dan mengulang dari nol. Lanjutkan?\')" style="display:inline-block;padding:12px 24px;background:#ef4444;color:white;text-decoration:none;border-radius:10px;font-weight:bold;">🔄 Reset Ulang Database (Fresh)</a>' .
                '</div></div>');
        }

        return response('<div style="background:#0f172a;color:#f8fafc;padding:30px;font-family:sans-serif;min-height:100vh;">' .
            '<h1 style="color:#f43f5e;margin-bottom:10px;">❌ Terjadi Kesalahan Saat Migrasi</h1>' .
            '<p style="color:#94a3b8;">Pastikan konfigurasi DB_HOST, DB_DATABASE, DB_USERNAME, dan DB_PASSWORD di file .env sudah benar.</p>' .
            '<pre style="background:#1e293b;padding:15px;border-radius:8px;color:#fca5a5;overflow-x:auto;margin-top:20px;">' . e($e->getMessage()) . '</pre>' .
            '<div style="margin-top:20px;">' .
            '<a href="' . url('/system/migrate?key=' . $secret . '&fresh=1') . '" style="display:inline-block;padding:10px 20px;background:#ef4444;color:white;text-decoration:none;border-radius:8px;font-weight:bold;">Coba Reset Ulang Database (Fresh)</a>' .
            '</div></div>', 500);
    }
})->name('system.migrate');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Administrator Routes (/admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

        // Devices
        Route::get('/devices', [AdminDeviceController::class, 'index'])->name('devices.index');
        Route::get('/devices/create', [AdminDeviceController::class, 'create'])->name('devices.create');
        Route::post('/devices', [AdminDeviceController::class, 'store'])->name('devices.store');
        Route::get('/devices/{device}', [AdminDeviceController::class, 'show'])->name('devices.show');
        Route::get('/devices/{device}/edit', [AdminDeviceController::class, 'edit'])->name('devices.edit');
        Route::put('/devices/{device}', [AdminDeviceController::class, 'update'])->name('devices.update');
        Route::delete('/devices/{device}', [AdminDeviceController::class, 'destroy'])->name('devices.destroy');
        Route::post('/devices/{device}/status', [AdminDeviceController::class, 'updateStatus'])->name('devices.status');
        Route::post('/devices/{device}/reset', [AdminDeviceController::class, 'reset'])->name('devices.reset');
        Route::get('/devices/{device}/download/svg', [AdminDeviceController::class, 'downloadSvg'])->name('devices.download.svg');
        Route::get('/devices/{device}/download/png', [AdminDeviceController::class, 'downloadPng'])->name('devices.download.png');

        // Businesses
        Route::resource('businesses', AdminBusinessController::class);

        // Users
        Route::resource('users', AdminUserController::class);

        // Telemetry Scan Logs
        Route::get('/scans', [AdminScanLogController::class, 'index'])->name('scans.index');
    });

/*
|--------------------------------------------------------------------------
| Business Owner Portal Routes (/portal)
|--------------------------------------------------------------------------
*/
Route::prefix('portal')
    ->name('portal.')
    ->middleware(['auth', 'role:business_owner'])
    ->group(function () {
        Route::get('/', [PortalDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [PortalDashboardController::class, 'index'])->name('dashboard.index');

        // Devices
        Route::get('/devices', [PortalDeviceController::class, 'index'])->name('devices.index');
        Route::get('/devices/{device}', [PortalDeviceController::class, 'show'])->name('devices.show');
        Route::put('/devices/{device}', [PortalDeviceController::class, 'update'])->name('devices.update');

        // Business profile and Google Review link settings
        Route::get('/settings', [PortalSettingsController::class, 'index'])->name('settings');
        Route::put('/settings/business/{business}', [PortalSettingsController::class, 'updateBusiness'])->name('settings.business.update');

        // Analytics
        Route::get('/analytics', [PortalAnalyticsController::class, 'index'])->name('analytics');
    });
