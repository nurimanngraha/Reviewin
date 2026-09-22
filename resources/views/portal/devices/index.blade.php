@extends('layouts.portal')

@section('title', 'Perangkat Saya - CreTech')
@section('page_title', 'Perangkat QR Code & NFC Saya')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Koleksi Perangkat Google Review</h2>
            <p class="text-xs text-slate-500">Semua kartu NFC, stiker meja, dan stand akrilik yang aktif terhubung ke toko Anda.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500 font-medium">Ingin menambah kartu baru?</span>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                Scan kartu baru dari Admin untuk aktivasi otomatis
            </span>
        </div>
    </div>

    <!-- Devices Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($devices as $device)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <!-- Card Top -->
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <div>
                            <span class="font-mono text-sm font-extrabold text-slate-900 block tracking-tight">{{ $device->device_code }}</span>
                            <h3 class="text-base font-bold text-slate-800 mt-0.5">{{ $device->name }}</h3>
                            <span class="text-[11px] text-slate-500 block">{{ $device->business?->name }}</span>
                            @if(!empty($device->activation_code))
                                <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 mt-1" title="Kode Kartu (Activation Code) Fisik">
                                    <i class="fas fa-key text-[9px] text-amber-600"></i> Kode: {{ $device->activation_code }}
                                </span>
                            @endif
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                              {{ $device->status === 'active' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst($device->status) }}
                        </span>
                    </div>

                    <!-- Usage Stats -->
                    <div class="grid grid-cols-2 gap-2 p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs text-center my-4">
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase font-semibold block">Total Scans</span>
                            <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ number_format($device->total_scans) }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase font-semibold block">QR / NFC</span>
                            <span class="text-xs font-bold text-slate-800 mt-1 block">{{ $device->total_qr_scans }} / {{ $device->total_nfc_scans }}</span>
                        </div>
                    </div>

                    <!-- Physical Unit Info -->
                    <div class="text-xs text-slate-500 mb-4 p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-500">Tipe Kartu Fisik</span>
                        <span class="font-mono font-bold text-[11px] text-slate-800 uppercase">{{ str_replace('_', ' + ', $device->type) }}</span>
                    </div>
                </div>

                <!-- Actions Footer -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('portal.devices.show', $device) }}" 
                       class="flex-1 py-2 px-3 text-center rounded-xl font-semibold text-xs text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-colors">
                        Kelola Perangkat
                    </a>

                    <a href="{{ route('device.redirect', $device->device_code) }}" target="_blank" title="Uji Coba Alur Redirect Google Review"
                       class="p-2 rounded-xl text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                <p class="text-sm font-semibold text-slate-600">Belum ada perangkat yang terhubung ke akun Anda.</p>
                <p class="text-xs text-slate-400 mt-1">Dapatkan kartu QR Code atau tag NFC dari Admin, lalu scan untuk pertama kali untuk aktivasi!</p>
            </div>
        @endforelse
    </div>

    @if($devices->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200">
            {{ $devices->links() }}
        </div>
    @endif

</div>
@endsection
