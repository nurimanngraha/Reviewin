@extends('layouts.admin')

@section('title', 'Manajemen Perangkat QR & NFC - ReviewIn')
@section('page_title', 'Daftar Perangkat QR & NFC')

@section('content')
<div class="space-y-6" x-data="{ resetModal: false, selectedDevice: null, resetUrl: '' }">

    <!-- Top Action & Filter Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-200/80 rounded-xl overflow-x-auto text-xs font-semibold">
            <a href="{{ route('admin.devices.index') }}" 
               class="px-3 py-1.5 rounded-lg transition-all {{ empty($filters['status']) ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.devices.index', ['status' => 'unactivated']) }}" 
               class="px-3 py-1.5 rounded-lg transition-all {{ ($filters['status'] ?? '') === 'unactivated' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Belum Aktif ({{ $counts['unactivated'] }})
            </a>
            <a href="{{ route('admin.devices.index', ['status' => 'active']) }}" 
               class="px-3 py-1.5 rounded-lg transition-all {{ ($filters['status'] ?? '') === 'active' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Aktif ({{ $counts['active'] }})
            </a>
            <a href="{{ route('admin.devices.index', ['status' => 'inactive']) }}" 
               class="px-3 py-1.5 rounded-lg transition-all {{ ($filters['status'] ?? '') === 'inactive' ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Nonaktif ({{ $counts['inactive'] }})
            </a>
            <a href="{{ route('admin.devices.index', ['status' => 'blocked']) }}" 
               class="px-3 py-1.5 rounded-lg transition-all {{ ($filters['status'] ?? '') === 'blocked' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Diblokir ({{ $counts['blocked'] }})
            </a>
        </div>

        <a href="{{ route('admin.devices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-sm transition-all whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>+ Buat Perangkat Baru</span>
        </a>
    </div>

    <!-- Filters Search & Business Dropdown -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.devices.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @if(!empty($filters['status']))
                <input type="hidden" name="status" value="{{ $filters['status'] }}">
            @endif

            <div>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                       placeholder="Cari kode (REV-...) atau nama..."
                       class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-brand-500 focus:ring-brand-500">
            </div>

            <div>
                <select name="business_id" class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">-- Semua Bisnis --</option>
                    @foreach($businesses as $b)
                        <option value="{{ $b->id }}" {{ ($filters['business_id'] ?? '') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition-colors">
                    Terapkan Filter
                </button>
                @if(!empty($filters['search']) || !empty($filters['business_id']) || !empty($filters['status']))
                    <a href="{{ route('admin.devices.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Devices Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4">Perangkat & Kode</th>
                        <th class="py-3.5 px-4">Bisnis Terhubung</th>
                        <th class="py-3.5 px-4">Tipe</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Penggunaan</th>
                        <th class="py-3.5 px-4">Scan Terakhir</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($devices as $device)
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            
                            <!-- Device Code & Label -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-slate-900 text-white flex items-center justify-center font-mono font-bold text-xs flex-shrink-0">
                                        QR
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.devices.show', $device) }}" class="font-mono font-bold text-slate-900 hover:text-brand-600 block text-xs">
                                            {{ $device->device_code }}
                                        </a>
                                        <span class="text-[11px] text-slate-500 block">{{ $device->name }}</span>
                                        @if(!empty($device->activation_code))
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200 mt-0.5" title="Kode Kartu (Activation Code) untuk aktivasi">
                                                <i class="fas fa-key text-[9px] text-amber-600"></i> {{ $device->activation_code }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Business -->
                            <td class="py-3.5 px-4">
                                @if($device->business)
                                    <a href="{{ route('admin.businesses.show', $device->business) }}" class="font-bold text-slate-900 hover:text-brand-600 block">
                                        {{ $device->business->name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400">{{ $device->business->category ?? 'Bisnis' }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Belum Terhubung
                                    </span>
                                @endif
                            </td>

                            <!-- Type -->
                            <td class="py-3.5 px-4">
                                <span class="font-mono uppercase font-semibold text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' + ', $device->type) }}
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4">
                                @if($device->status === 'unactivated')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Belum Aktif
                                    </span>
                                @elseif($device->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Aktif
                                    </span>
                                @elseif($device->status === 'inactive')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @elseif($device->status === 'blocked')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                        Diblokir
                                    </span>
                                @endif
                            </td>

                            <!-- Total Scans -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 text-xs">
                                    {{ number_format($device->total_scans) }} <span class="text-[10px] text-slate-400 font-normal">scans</span>
                                </div>
                                <div class="text-[10px] text-slate-500">
                                    QR: {{ $device->total_qr_scans }} &bull; NFC: {{ $device->total_nfc_scans }}
                                </div>
                            </td>

                            <!-- Last Scanned -->
                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                {{ $device->last_scanned_at ? $device->last_scanned_at->diffForHumans() : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    
                                    <!-- View/QR Detail -->
                                    <a href="{{ route('admin.devices.show', $device) }}" title="Lihat Detail & QR Code"
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-brand-600 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>

                                    <!-- Download SVG -->
                                    <a href="{{ route('admin.devices.download.svg', $device) }}" title="Unduh QR SVG"
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-brand-600 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>

                                    <!-- Test Redirect -->
                                    <a href="{{ route('device.redirect', $device->device_code) }}" target="_blank" title="Test Dynamic Redirect /r/{{ $device->device_code }}"
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-600 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>

                                    <!-- Reset Button -->
                                    @if($device->status !== 'unactivated')
                                        <button type="button" 
                                                @click="selectedDevice = '{{ $device->device_code }}'; resetUrl = '{{ route('admin.devices.reset', $device) }}'; resetModal = true;"
                                                title="Reset Device ke Unactivated"
                                                class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        </button>
                                    @endif

                                    <!-- Edit -->
                                    <a href="{{ route('admin.devices.edit', $device) }}" title="Edit Data"
                                       class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                Tidak ada perangkat yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($devices->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $devices->links() }}
            </div>
        @endif
    </div>

    <!-- Reset Device Modal -->
    <div x-show="resetModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="resetModal = false" class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl border border-slate-100 text-left">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Konfirmasi Reset Perangkat</h3>
            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                Apakah Anda yakin ingin mereset perangkat <span class="font-mono font-bold text-slate-900" x-text="selectedDevice"></span>? Status akan kembali menjadi <strong class="text-amber-600">unactivated</strong> dan koneksi ke bisnis akan dilepas.
            </p>

            <form :action="resetUrl" method="POST" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Alasan Reset (Opsional)</label>
                    <input type="text" name="reason" placeholder="Contoh: Pergantian kepemilikan tenant" 
                           class="w-full rounded-xl border-slate-300 text-xs py-2 px-3">
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="resetModal = false" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 shadow">
                        Ya, Reset Perangkat
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
