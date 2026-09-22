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
