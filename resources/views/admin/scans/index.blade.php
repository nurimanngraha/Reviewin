@extends('layouts.admin')

@section('title', 'Log Telemetri Scan & Tap - CreTech')
@section('page_title', 'Log Telemetri Pemindaian QR & NFC')

@section('content')
<div class="space-y-6">

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.scans.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            
            <div>
                <select name="scan_type" class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">-- Semua Tipe (QR & NFC) --</option>
                    <option value="qr" {{ ($filters['scan_type'] ?? '') === 'qr' ? 'selected' : '' }}>Hanya QR Code</option>
                    <option value="nfc" {{ ($filters['scan_type'] ?? '') === 'nfc' ? 'selected' : '' }}>Hanya NFC Tap</option>
                </select>
            </div>

            <div>
                <select name="device_id" class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">-- Semua Perangkat --</option>
                    @foreach($devices as $d)
                        <option value="{{ $d->id }}" {{ ($filters['device_id'] ?? '') == $d->id ? 'selected' : '' }}>
                            {{ $d->device_code }} ({{ $d->name }})
                        </option>
                    @endforeach
                </select>
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
                <input type="date" name="date" value="{{ $filters['date'] ?? '' }}" 
                       class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-brand-500 focus:ring-brand-500">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800">
                    Filter
                </button>
            </div>

        </form>
    </div>

    <!-- Scans Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4">Waktu Pemindaian</th>
                        <th class="py-3.5 px-4">Tipe Akses</th>
                        <th class="py-3.5 px-4">Perangkat</th>
                        <th class="py-3.5 px-4">Bisnis Target</th>
                        <th class="py-3.5 px-4">Platform & Browser</th>
                        <th class="py-3.5 px-4">Device Pelanggan</th>
                        <th class="py-3.5 px-4 text-right">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($scans as $scan)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-900">
                                {{ $scan->scanned_at->translatedFormat('d M Y, H:i:s') }}
                                <span class="text-[10px] text-slate-400 block font-sans">{{ $scan->scanned_at->diffForHumans() }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                      {{ $scan->scan_type === 'nfc' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-brand-100 text-brand-800 border border-brand-200' }}">
                                    {{ $scan->scan_type === 'nfc' ? 'Tap NFC' : 'Scan QR' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('admin.devices.show', $scan->device) }}" class="font-mono font-bold text-slate-900 hover:text-brand-600 block">
                                    {{ $scan->device?->device_code }}
                                </a>
                                <span class="text-[10px] text-slate-500">{{ $scan->device?->name }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                {{ $scan->business?->name ?? 'Bisnis' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800">{{ $scan->platform }}</span>
                                <span class="text-slate-400 text-[10px] block">{{ $scan->browser }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-medium text-slate-700 text-[10px]">
                                    {{ $scan->device_type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-[11px] text-slate-500">
                                {{ $scan->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                Belum ada log aktivitas pemindaian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($scans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $scans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
