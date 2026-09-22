@extends('layouts.app')

@section('title', 'Link Google Review Belum Diatur - CreTech')

@section('body')
<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto w-full text-center">
        
        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-5 shadow-sm border border-amber-200">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-slate-900">Link Google Review Belum Diatur</h1>
        <p class="mt-2 text-sm text-slate-600">
            Perangkat <span class="font-mono font-bold text-slate-900">{{ $device->device_code }}</span> sudah aktif, namun pemilik bisnis <span class="font-bold text-slate-900">{{ $device->business?->name }}</span> belum memasukkan URL Google Review.
        </p>

        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 text-sm">
                Beranda
            </a>
            <a href="{{ route('portal.settings') }}" class="px-5 py-2.5 rounded-xl font-medium text-white bg-emerald-600 hover:bg-emerald-700 text-sm shadow">
                Atur Link Google Review
            </a>
        </div>

    </div>
</div>
@endsection
