@extends('layouts.portal')

@section('title', 'Perangkat ' . $device->device_code . ' - CreTech')

@section('content')
<div class="space-y-6" x-data="{ copiedNfc: false }">
    <!-- Breadcrumb & Status Topbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <a href="{{ route('portal.devices.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors mb-1">
                <i class="fas fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Daftar Perangkat</span>
            </a>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Perangkat: <span class="font-mono text-emerald-600">{{ $device->device_code }}</span>
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                {{ $device->status === 'active' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-700' }}">
                Status: {{ $device->status }}
            </span>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Realistic Virtual Card Representation -->
        <div class="space-y-4">
            
            <!-- Virtual Card Mockup Container -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-tr from-slate-950 via-slate-900 to-slate-800 p-6 shadow-xl border border-slate-700 text-white min-h-[220px] flex flex-col justify-between group">
                <!-- Card Background Accents -->
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-36 h-36 bg-blue-500/10 rounded-full blur-xl pointer-events-none"></div>

                <!-- Top Row: Card Brand & Status -->
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-white p-1 flex items-center justify-center shadow">
                            <img src="{{ asset('assets/CreTechlogopersegi.svg') }}" alt="CreTech" class="w-full h-full object-contain">
                        </div>
                        <span class="font-extrabold text-xs tracking-wider uppercase text-slate-200">CreTech Card</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Aktif
                    </span>
                </div>

                <!-- EMV Chip & Contactless Wave -->
                <div class="flex items-center justify-between my-4 relative z-10">
                    <div class="w-10 h-8 rounded bg-amber-300/80 border border-amber-400/60 shadow-inner flex items-center justify-center">
                        <div class="w-7 h-5 border border-amber-600/40 rounded-xs grid grid-cols-2 gap-0.5 p-0.5 opacity-60">
                            <div class="border-r border-amber-700/30"></div>
                            <div></div>
                        </div>
                    </div>
                    <div class="text-slate-400 flex items-center gap-1" title="Contactless NFC & QR Smart Card">
                        <svg class="w-6 h-6 rotate-90 text-emerald-400/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                    </div>
                </div>

                <!-- Device Code -->
                <div class="my-4 relative z-10">
                    <span class="text-[10px] uppercase font-semibold text-slate-400 tracking-wider block">Kode Kartu Fisik</span>
                    <span class="font-mono text-xl font-extrabold text-white tracking-widest block mt-0.5">{{ $device->device_code }}</span>
                </div>

                <!-- Business & Type -->
                <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs relative z-10">
                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Toko</span>
                        <span class="font-bold text-white block truncate max-w-[140px]">{{ $device->business?->name }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Tipe Unit</span>
                        <span class="font-mono font-bold text-emerald-400 uppercase text-[11px] block">{{ str_replace('_', ' + ', $device->type) }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Specs & Order Notice -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center gap-1.5 mb-2">
                        <i class="fas fa-info-circle text-emerald-600"></i>
                        <span>Informasi Unit Fisik</span>
                    </h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Kartu fisik ini telah diprogram langsung dengan link Google Review toko Anda. Pelanggan cukup mendekatkan smartphone (NFC) atau scan kamera ke kartu fisik di meja kasir.
                    </p>
                </div>

                <div class="divide-y divide-slate-100 text-xs">
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-slate-500">Status Perangkat</span>
                        <span class="font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">Aktif & Siap Digunakan</span>
                    </div>
                    @if(!empty($device->activation_code))
                        <div class="py-2 flex items-center justify-between">
                            <span class="text-slate-500">Kode Aktivasi Kartu</span>
                            <span class="font-mono font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 text-[11px]">
                                {{ $device->activation_code }}
                            </span>
                        </div>
                    @endif
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-slate-500">Terhubung Sejak</span>
                        <span class="font-medium text-slate-800">
                            {{ $device->activated_at ? $device->activated_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex items-center justify-between">
                        <span class="text-slate-500">Scan Terakhir</span>
                        <span class="font-medium text-slate-800">
                            {{ $device->last_scanned_at ? $device->last_scanned_at->diffForHumans() : 'Belum pernah' }}
                        </span>
                    </div>
                </div>

                <!-- Callout: Need more units? -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600">
                    <div class="flex items-center gap-1.5 text-slate-900 font-bold text-[11px] mb-1">
                        <i class="fas fa-plus-circle text-emerald-600"></i>
                        <span>Butuh Kartu Tambahan?</span>
                    </div>
                    <p class="text-[11px] leading-relaxed text-slate-500">
                        Untuk menambah unit kartu fisik atau stand akrilik baru pada kasir atau meja lainnya, silakan hubungi <strong>Administrator</strong>.
                    </p>
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
