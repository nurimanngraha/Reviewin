@extends('layouts.admin')

@section('title', 'Admin Dashboard - ReviewIn')
@section('page_title', 'Ringkasan & Statistik Sistem')

@section('content')
<div class="space-y-6">

    <!-- KPI Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Devices -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Perangkat</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalDevices) }}</h3>
                <span class="text-[11px] text-slate-400 mt-1 block">QR & NFC Terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            </div>
        </div>

        <!-- Unactivated Devices -->
        <div class="bg-white rounded-2xl p-5 border border-amber-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Belum Diaktivasi</p>
                <h3 class="text-2xl font-extrabold text-amber-600 mt-1">{{ number_format($unactivatedDevices) }}</h3>
                <a href="{{ route('admin.devices.index', ['status' => 'unactivated']) }}" class="text-[11px] text-amber-600 hover:underline mt-1 block">Lihat device &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        <!-- Active Devices -->
        <div class="bg-white rounded-2xl p-5 border border-emerald-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Perangkat Aktif</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($activeDevices) }}</h3>
                <span class="text-[11px] text-emerald-600 mt-1 block">Terkoneksi ke Bisnis</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        <!-- Total Businesses -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Bisnis</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalBusinesses) }}</h3>
                <a href="{{ route('admin.businesses.index') }}" class="text-[11px] text-brand-600 hover:underline mt-1 block">Kelola bisnis &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
        </div>

    </div>

    <!-- Second Row: Scans & Taps Counter + Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Scans Overview Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Total Penggunaan Pelanggan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pemindaian QR Code dan tap NFC yang sukses diarahkan ke Google Review.</p>
                
                <div class="mt-6">
                    <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalScans) }}</span>
                    <span class="text-xs text-slate-500 ml-1">total redirects</span>
                </div>

                <div class="mt-6 space-y-4">
                    <!-- QR Scan Bar -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span> QR Code Scan
                            </span>
                            <span class="text-slate-900 font-bold">{{ number_format($totalQrScans) }} ({{ $totalScans > 0 ? round(($totalQrScans / $totalScans) * 100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-brand-600 h-2 rounded-full" style="width: {{ $totalScans > 0 ? ($totalQrScans / $totalScans) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- NFC Tap Bar -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> NFC Card Tap
                            </span>
                            <span class="text-slate-900 font-bold">{{ number_format($totalNfcScans) }} ({{ $totalScans > 0 ? round(($totalNfcScans / $totalScans) * 100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $totalScans > 0 ? ($totalNfcScans / $totalScans) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-6 border-t border-slate-100">
                <a href="{{ route('admin.scans.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                    <span>Buka Semua Log Telemetri</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

        <!-- Trend Chart -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm lg:col-span-2 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tren Penggunaan 14 Hari Terakhir</h3>
                    <p class="text-xs text-slate-500">Perbandingan harian antara pemindaian QR Code dan tap NFC.</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-medium">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span> QR</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> NFC</span>
                </div>
            </div>
            <div class="flex-1 min-h-[220px]">
                <canvas id="scansChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Third Row: Recent Scans & Recent Activations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Scans / Redirects -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Aktivitas Scan Pelanggan Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Redirect langsung ke Google Review bisnis</p>
                </div>
                <a href="{{ route('admin.scans.index') }}" class="text-xs text-brand-600 font-semibold hover:underline">Semua Log &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1 overflow-x-auto">
                @forelse($recentScans as $scan)
                    <div class="p-4 flex items-center justify-between text-xs hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-1 rounded font-bold uppercase text-[10px] {{ $scan->scan_type === 'nfc' ? 'bg-emerald-100 text-emerald-800' : 'bg-brand-100 text-brand-800' }}">
                                {{ $scan->scan_type }}
                            </span>
                            <div>
                                <p class="font-bold text-slate-900">{{ $scan->business?->name ?? 'Bisnis' }}</p>
                                <p class="text-[11px] text-slate-500">Device: <span class="font-mono text-slate-700">{{ $scan->device?->device_code }}</span> ({{ $scan->device_type }} &bull; {{ $scan->platform }})</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-500 block text-[11px]">{{ $scan->scanned_at->diffForHumans() }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $scan->ip_address }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">Belum ada pemindaian tercatat.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activations -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Riwayat Aktivasi Device Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perangkat yang baru dihubungkan pemilik bisnis</p>
                </div>
                <a href="{{ route('admin.devices.index', ['status' => 'active']) }}" class="text-xs text-brand-600 font-semibold hover:underline">Perangkat Aktif &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100 flex-1 overflow-x-auto">
                @forelse($recentActivations as $act)
                    <div class="p-4 flex items-center justify-between text-xs hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                ✓
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $act->business?->name }}</p>
                                <p class="text-[11px] text-slate-500">Kode: <span class="font-mono text-slate-700 font-bold">{{ $act->device?->device_code }}</span> &bull; Oleh: {{ $act->user?->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-500 block text-[11px]">{{ $act->activated_at->translatedFormat('d M, H:i') }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">Belum ada aktivasi tercatat.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('scansChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'QR Scan',
                        data: {!! json_encode($chartQrData) !!},
                        backgroundColor: '#4f46e5',
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
