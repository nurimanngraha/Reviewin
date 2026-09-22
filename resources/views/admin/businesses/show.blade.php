@extends('layouts.admin')

@section('title', 'Detail Bisnis ' . $business->name . ' - ReviewIn')
@section('page_title', 'Detail Bisnis: ' . $business->name)

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.businesses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            <span>Kembali ke Daftar Bisnis</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.businesses.edit', $business) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                Edit Bisnis
            </a>
        </div>
    </div>

    <!-- Overview Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-2.5 py-0.5 rounded-full border border-brand-100">
                    {{ $business->category ?? 'Bisnis' }}
                </span>
                <h2 class="text-xl font-extrabold text-slate-900 mt-2">{{ $business->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ $business->address ?: 'Alamat belum diatur' }}</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ $business->google_review_url }}" target="_blank"
                   class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    <span>Cek Link Google Review</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-6">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 text-xs block">Pemilik Bisnis</span>
                <span class="text-sm font-bold text-slate-900 mt-1 block">{{ $business->user?->name }}</span>
                <span class="text-[11px] text-slate-500">{{ $business->user?->email }}</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 text-xs block">Total Perangkat</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block">{{ $business->devices->count() }}</span>
                <span class="text-[11px] text-emerald-600 font-semibold">{{ $business->devices->where('status', 'active')->count() }} unit aktif</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 text-xs block">Total Review Traffic</span>
                <span class="text-2xl font-extrabold text-brand-600 mt-1 block">{{ number_format($totalScans) }}</span>
                <span class="text-[11px] text-slate-500">Redirects tercatat</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-slate-400 text-xs block">Rasio QR vs NFC</span>
                <span class="text-sm font-bold text-slate-900 mt-1 block">QR: {{ $qrScans }} &bull; NFC: {{ $nfcScans }}</span>
                <span class="text-[11px] text-slate-500">{{ $totalScans > 0 ? round(($nfcScans / $totalScans) * 100) : 0 }}% via tap NFC</span>
            </div>
        </div>
    </div>

    <!-- Connected Devices Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Perangkat yang Terhubung ke {{ $business->name }}</h3>
            <a href="{{ route('admin.devices.create') }}" class="text-xs font-semibold text-brand-600 hover:underline">+ Tambah Device</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Kode & Label</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Total Scans</th>
                        <th class="py-3 px-4">Terakhir Digunakan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($business->devices as $dev)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.devices.show', $dev) }}" class="font-mono font-bold text-slate-900 hover:text-brand-600">
                                    {{ $dev->device_code }}
                                </a>
                                <span class="text-[11px] text-slate-500 block">{{ $dev->name }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono uppercase text-[10px]">{{ $dev->type }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                      {{ $dev->status === 'active' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                                    {{ ucfirst($dev->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ number_format($dev->total_scans) }}</td>
                            <td class="py-3 px-4 text-slate-500 text-[11px]">{{ $dev->last_scanned_at ? $dev->last_scanned_at->diffForHumans() : '-' }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.devices.show', $dev) }}" class="text-xs font-semibold text-brand-600 hover:underline">
                                    Lihat QR &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada perangkat yang terhubung ke bisnis ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
