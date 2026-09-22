@extends('layouts.app')

@section('title', 'Perangkat Sudah Aktif - ' . $device->device_code)

@section('body')
<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto w-full text-center">
        
        <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-5 shadow-sm border border-emerald-200">
            <svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-900">Perangkat Ini Sudah Aktif!</h1>
        <p class="mt-2 text-sm text-slate-600">
            Perangkat <span class="font-mono font-bold text-slate-900">{{ $device->device_code }}</span> sudah terhubung dan siap melayani pelanggan.
        </p>

        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-left space-y-3">
            <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Nama Label:</span>
                <span class="font-bold text-slate-900">{{ $device->name }}</span>
            </div>
            <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Bisnis Terhubung:</span>
                <span class="font-bold text-emerald-700">{{ $device->business?->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center text-xs pb-3 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Total Pemindaian:</span>
                <span class="font-bold text-slate-900">{{ number_format($device->total_scans) }} kali</span>
            </div>
            <div class="text-xs pt-1">
                <span class="text-slate-500 font-medium block mb-1">Tujuan Google Review:</span>
                <a href="{{ $device->effective_review_url }}" target="_blank" class="text-brand-600 hover:text-brand-700 underline break-all font-mono text-[11px]">
                    {{ $device->effective_review_url }}
                </a>
            </div>
        </div>

        <div class="mt-6 flex flex-col gap-3">
            <a href="{{ route('device.redirect', $device->device_code) }}" 
               class="py-3 px-4 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 text-sm transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                <span>Uji Redirect Google Review (Sebagai Pelanggan)</span>
            </a>

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.devices.show', $device) }}" class="py-2.5 px-4 rounded-xl font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 text-sm transition-colors">
                        Kembali ke Detail Device Admin
                    </a>
                @elseif($device->business && $device->business->user_id === auth()->id())
                    <a href="{{ route('portal.devices.show', $device) }}" class="py-2.5 px-4 rounded-xl font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 text-sm transition-colors">
                        Buka di Portal Pemilik Bisnis
                    </a>
                @else
                    <a href="{{ route('portal.dashboard') }}" class="py-2.5 px-4 rounded-xl font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 text-sm transition-colors">
                        Kembali ke Dashboard Portal
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="py-2.5 px-4 rounded-xl font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 text-sm transition-colors">
                    Login Pemilik Bisnis
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection
