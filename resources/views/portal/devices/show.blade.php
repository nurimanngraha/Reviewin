@extends('layouts.portal')

@section('title', 'Perangkat ' . $device->device_code . ' - ReviewIn')
@section('page_title', 'Perangkat: ' . $device->device_code)

@section('content')
<div class="space-y-6" x-data="{ copiedNfc: false }">

    <div class="flex items-center justify-between">
        <a href="{{ route('portal.devices.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            <span>Kembali ke Daftar Perangkat</span>
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200">
            Status: {{ ucfirst($device->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: QR Code & NFC Box -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
                <!-- QR Code SVG Container -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl inline-block shadow-inner mb-4">
                    <div class="w-60 h-60 flex items-center justify-center mx-auto">
                        {!! $qrSvg !!}
                    </div>
                </div>

                <h3 class="font-mono font-extrabold text-lg text-slate-900 tracking-tight">{{ $device->device_code }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ $device->name }}</p>

                <!-- Download Buttons -->
                <div class="grid grid-cols-2 gap-2 mt-5">
                    <a href="{{ route('portal.devices.download.svg', $device) }}" 
                       class="py-2.5 px-3 rounded-xl font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 text-xs transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>Unduh SVG</span>
                    </a>
                    <a href="{{ route('portal.devices.download.png', $device) }}" 
                       class="py-2.5 px-3 rounded-xl font-semibold text-white bg-slate-900 hover:bg-slate-800 text-xs transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>Unduh PNG</span>
                    </a>
                </div>
            </div>

            <!-- NFC Configuration Box -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span>Link Kartu NFC</span>
                    </h4>
                    <span class="text-[10px] text-slate-400">Tag URL</span>
                </div>
                <div class="relative">
                    <input type="text" readonly value="{{ $device->nfc_url }}" id="nfcUrlInput"
                           class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-mono py-2 pl-3 pr-20 text-slate-700">
                    <button type="button" 
                            @click="navigator.clipboard.writeText('{{ $device->nfc_url }}'); copiedNfc = true; setTimeout(() => copiedNfc = false, 2000)"
                            class="absolute right-1 top-1 bottom-1 px-3 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 transition-colors">
                        <span x-text="copiedNfc ? 'Tersalin!' : 'Salin'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: Rename Label, Target Review, and Logs -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Rename & Target Review Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4">Pengaturan Label & Target Review</h3>

                <!-- Update Label Form -->
                <form action="{{ route('portal.devices.update', $device) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Label Perangkat</label>
                        <div class="flex gap-2">
                            <input type="text" id="name" name="name" value="{{ old('name', $device->name) }}" required
                                   class="flex-1 rounded-xl border-slate-300 text-sm py-2 px-3.5 focus:border-emerald-500 focus:ring-emerald-500">
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm">
                                Simpan Nama
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Current Review Target -->
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-semibold text-slate-700">Tujuan Google Review:</span>
                        <a href="{{ $device->effective_review_url }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:underline flex items-center gap-1">
                            <span>Uji Link Sekarang</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>
                    <p class="font-mono text-xs text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-200 break-all">
                        {{ $device->effective_review_url }}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-1">Untuk memperbarui link review semua perangkat toko ini, buka menu <a href="{{ route('portal.settings') }}" class="text-emerald-600 font-semibold underline">Pengaturan Bisnis</a>.</p>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm text-center">
                    <span class="text-slate-400 text-xs block">Total Pemindaian</span>
                    <span class="text-2xl font-extrabold text-slate-900 mt-1 block">{{ number_format($device->total_scans) }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm text-center">
                    <span class="text-brand-600 text-xs font-semibold block">Scan QR</span>
                    <span class="text-2xl font-extrabold text-brand-600 mt-1 block">{{ number_format($device->total_qr_scans) }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm text-center">
                    <span class="text-emerald-600 text-xs font-semibold block">Tap NFC</span>
                    <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">{{ number_format($device->total_nfc_scans) }}</span>
                </div>
            </div>

            <!-- Recent Scans on this Device -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Aktivitas Pemindaian Perangkat Ini</h4>
                    <span class="text-[11px] text-slate-400">15 scan terakhir</span>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($device->scans as $scan)
                        <div class="p-3.5 flex items-center justify-between hover:bg-slate-50">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $scan->scan_type === 'nfc' ? 'bg-emerald-100 text-emerald-800' : 'bg-brand-100 text-brand-800' }}">
                                    {{ $scan->scan_type }}
                                </span>
                                <span class="font-semibold text-slate-800">{{ $scan->device_type }} &bull; {{ $scan->platform }}</span>
                            </div>
                            <span class="text-slate-500 text-[11px]">{{ $scan->scanned_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400">Belum ada pemindaian pelanggan pada perangkat ini.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
