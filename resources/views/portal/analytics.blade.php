@extends('layouts.portal')

@section('title', 'Statistik & Analitik Penggunaan - CreTech')
@section('page_title', 'Statistik & Analitik Ulasan')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Pengunjung Dialihkan</span>
            <span class="text-3xl font-extrabold text-slate-900 mt-1 block">{{ number_format($totalScans) }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">Seluruh riwayat</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-brand-600 uppercase tracking-wider block">Pemindaian QR Code</span>
            <span class="text-3xl font-extrabold text-brand-600 mt-1 block">{{ number_format($totalQr) }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">{{ $totalScans > 0 ? round(($totalQr / $totalScans) * 100) : 0 }}% dari total</span>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider block">Tap Kartu NFC</span>
            <span class="text-3xl font-extrabold text-emerald-600 mt-1 block">{{ number_format($totalNfc) }}</span>
            <span class="text-[11px] text-slate-400 mt-1 block">{{ $totalScans > 0 ? round(($totalNfc / $totalScans) * 100) : 0 }}% dari total</span>
        </div>
    </div>

    <!-- Trend Chart -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Grafik Aktivitas Harian (14 Hari Terakhir)</h3>
                <p class="text-xs text-slate-500">Pergerakan pelanggan yang scan atau tap stand ulasan Anda.</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-medium">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> QR</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> NFC</span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="analyticsChart"></canvas>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('portal.analytics') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <select name="device_id" class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">-- Semua Perangkat --</option>
                    @foreach($devices as $d)
                        <option value="{{ $d->id }}" {{ ($filters['device_id'] ?? '') == $d->id ? 'selected' : '' }}>
                            {{ $d->name }} ({{ $d->device_code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="scan_type" class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">-- Semua Metode (QR & NFC) --</option>
                    <option value="qr" {{ ($filters['scan_type'] ?? '') === 'qr' ? 'selected' : '' }}>Hanya QR Code</option>
                    <option value="nfc" {{ ($filters['scan_type'] ?? '') === 'nfc' ? 'selected' : '' }}>Hanya NFC Tap</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition-colors">
                    Terapkan Filter
                </button>
                @if(!empty($filters['device_id']) || !empty($filters['scan_type']))
                    <a href="{{ route('portal.analytics') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Scans Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Rincian Riwayat Pemindaian Pelanggan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4">Perangkat</th>
                        <th class="py-3 px-4">Perangkat Pelanggan</th>
                        <th class="py-3 px-4 text-right">Hasil</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($scans as $scan)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-500">
                                {{ $scan->scanned_at->translatedFormat('d M Y, H:i:s') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $scan->scan_type === 'nfc' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $scan->scan_type === 'nfc' ? 'Tap NFC' : 'Scan QR' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-slate-900">{{ $scan->device?->device_code }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ $scan->device?->name }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-slate-800">{{ $scan->device_type }} &bull; {{ $scan->platform }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $scan->browser }}</span>
                            </td>
                            <td class="py-3 px-4 text-right text-emerald-600 font-semibold">
                                Dialihkan ke Google Review ✓
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada riwayat scan yang sesuai filter.</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'QR Scan',
                        data: {!! json_encode($chartQrData) !!},
                        backgroundColor: '#6366f1',
                        borderRadius: 6,
                    },
                    {
                        label: 'NFC Tap',
                        data: {!! json_encode($chartNfcData) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
