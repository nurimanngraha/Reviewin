@extends('layouts.app')

@section('title', 'Aktivasi Berhasil! - ' . $device->device_code)

@section('body')
<div class="min-h-screen bg-gradient-to-b from-emerald-50 via-slate-50 to-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg mx-auto w-full text-center">
        
        <!-- Animated Success Badge -->
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/30 scale-110">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200 mb-3">
            Status: Active & Terhubung
        </span>

        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Aktivasi Perangkat Berhasil!</h1>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
            Perangkat <span class="font-mono font-bold text-slate-900">{{ $device->device_code }}</span> telah berhasil terhubung secara permanen dengan bisnis Anda.
        </p>

        <!-- Summary Card -->
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-left space-y-3">
            <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Bisnis:</span>
                <span class="font-bold text-slate-900">{{ $device->business?->name }}</span>
            </div>
            <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Label Perangkat:</span>
                <span class="font-bold text-slate-900">{{ $device->name }}</span>
            </div>
            <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Waktu Aktivasi:</span>
                <span class="font-semibold text-slate-700">{{ $device->activated_at?->translatedFormat('d F Y, H:i') ?? now()->translatedFormat('d F Y, H:i') }}</span>
            </div>
            @if(!empty($device->business?->google_place_id))
            <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Google Place ID:</span>
                <span class="font-mono font-bold text-slate-800">{{ $device->business->google_place_id }}</span>
            </div>
            @endif
            <div class="text-xs pt-1">
                <span class="text-slate-500 font-medium block mb-1">Target Link Google Review:</span>
                <p class="font-mono text-[11px] text-slate-800 bg-slate-50 p-2.5 rounded-lg border border-slate-200 break-all">
                    {{ $device->effective_review_url }}
                </p>
            </div>
        </div>

        <!-- Next Step Notice -->
        <div class="mt-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-left flex items-start gap-3">
            <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">
                i
            </div>
            <p class="text-xs text-blue-950 leading-relaxed font-medium">
                <strong>Alur Berikutnya:</strong> Setiap scan QR Code atau tap NFC berikutnya oleh pelanggan tidak akan lagi masuk ke halaman aktivasi ini. Sistem akan secara otomatis mengenali perangkat aktif, mencatat data pemindaian, dan langsung mengarahkan pelanggan ke Google Review Anda!
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('device.redirect', $device->device_code) }}" 
               class="flex-1 py-3 px-4 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 text-sm transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                <span>Uji Coba Sekarang</span>
            </a>
            <a href="{{ route('portal.dashboard') }}" 
               class="flex-1 py-3 px-4 rounded-xl font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 text-sm transition-colors flex items-center justify-center gap-2">
                <span>Buka Dashboard Bisnis</span>
            </a>
        </div>

    </div>
</div>
@endsection
