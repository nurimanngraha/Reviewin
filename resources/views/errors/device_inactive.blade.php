@extends('layouts.app')

@section('title', 'Perangkat Tidak Aktif - ReviewIn')

@section('body')
<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto w-full text-center">
        
        <div class="w-20 h-20 rounded-3xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-6 shadow-sm border border-amber-200">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>

        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $device->status === 'blocked' ? 'bg-rose-100 text-rose-800 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }} mb-3">
            Perangkat {{ ucfirst($device->status) }}
        </span>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Perangkat Tidak Dapat Digunakan</h1>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            Perangkat Google Review ini saat ini berstatus <span class="font-semibold text-slate-800">{{ $device->status }}</span> sehingga pemindaian ditangguhkan.
        </p>

        <!-- Device Card -->
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-5 text-left text-xs space-y-2.5">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <span class="text-slate-500 font-medium">Kode Perangkat:</span>
                <span class="font-mono font-bold text-slate-900">{{ $device->device_code }}</span>
            </div>
            @if($device->business)
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <span class="text-slate-500 font-medium">Bisnis:</span>
                    <span class="font-bold text-slate-900">{{ $device->business->name }}</span>
                </div>
            @endif
            <div class="flex justify-between items-center">
                <span class="text-slate-500 font-medium">Status:</span>
                <span class="font-bold {{ $device->status === 'blocked' ? 'text-rose-600' : 'text-amber-600' }}">
                    {{ ucfirst($device->status) }}
                </span>
            </div>
        </div>

        <p class="mt-5 text-xs text-slate-500">
            Jika Anda adalah pemilik bisnis atau pengelola perangkat ini, silakan hubungi Administrator atau login ke portal untuk mengaktifkan kembali perangkat.
        </p>

        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 text-sm transition-colors">
                Beranda
            </a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.devices.show', $device) }}" class="px-5 py-2.5 rounded-xl font-medium text-white bg-brand-600 hover:bg-brand-700 text-sm shadow">
                        Kelola di Admin
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl font-medium text-white bg-slate-900 hover:bg-slate-800 text-sm shadow">
                    Login Pengelola
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection
