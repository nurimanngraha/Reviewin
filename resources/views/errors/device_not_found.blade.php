@extends('layouts.app')

@section('title', 'Perangkat Tidak Ditemukan - ReviewIn')

@section('body')
<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto w-full text-center">
        
        <div class="w-20 h-20 rounded-3xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-200">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200 mb-3">
            404 - Device Not Found
        </span>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Perangkat Tidak Terdaftar</h1>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            Kode perangkat <span class="font-mono font-bold text-slate-900 bg-slate-200 px-2 py-0.5 rounded">{{ $device_code ?? 'TIDAK_DIKENAL' }}</span> tidak ditemukan dalam database sistem ReviewIn.
        </p>

        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-5 text-left text-xs text-slate-600 space-y-2">
            <p class="font-semibold text-slate-900">Kemungkinan penyebab:</p>
            <ul class="list-disc pl-4 space-y-1">
                <li>QR Code atau tag NFC belum dicetak atau didaftarkan oleh Administrator.</li>
                <li>Terdapat kesalahan penulisan kode URL pada perangkat.</li>
                <li>Perangkat sudah dihapus permanen oleh pengelola.</li>
            </ul>
        </div>

        <div class="mt-6">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-white bg-slate-900 hover:bg-slate-800 shadow text-sm transition-colors">
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection
