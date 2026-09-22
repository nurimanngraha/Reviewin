@extends('layouts.portal')

@section('title', 'Dashboard Bisnis - ReviewIn')
@section('page_title', 'Dashboard Bisnis')

@section('content')
<div class="space-y-6">

    <!-- Active Business Announcement / Review Link Header -->
    @if($businesses->count() > 0)
        @php $mainBiz = $businesses->first(); @endphp
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 rounded-2xl p-6 text-white shadow-lg shadow-emerald-600/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-white backdrop-blur mb-2">
                    {{ $mainBiz->category ?? 'Bisnis Terdaftar' }}
                </span>
                <h2 class="text-2xl font-extrabold tracking-tight">{{ $mainBiz->name }}</h2>
                <p class="text-xs text-emerald-100 mt-1 max-w-xl">
                    {{ $mainBiz->address ?: 'Alamat belum diisi' }} &bull; {{ $mainBiz->phone ?: 'No kontak belum diisi' }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $mainBiz->google_review_url }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl text-xs font-bold text-emerald-900 bg-white hover:bg-emerald-50 shadow transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    <span>Uji Link Google Review</span>
                </a>
                <a href="{{ route('portal.settings') }}" class="px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition-colors">
                    Edit Link
                </a>
            </div>
        </div>
    @endif

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Devices -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Perangkat Terhubung</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalDevices) }}</h3>
                <span class="text-[11px] text-emerald-600 font-medium mt-1 block">{{ $activeDevices }} aktif & siap scan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            </div>
        </div>

        <!-- Total Reviews / Scans -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Review Traffic</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalScans) }}</h3>
                <span class="text-[11px] text-slate-400 mt-1 block">Pelanggan diarahkan</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
            </div>
        </div>

        <!-- QR Scans -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-600 uppercase tracking-wider">Pemindaian QR Code</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalQrScans) }}</h3>
                <span class="text-[11px] text-slate-400 mt-1 block">{{ $totalScans > 0 ? round(($totalQrScans / $totalScans) * 100) : 0 }}% dari total traffic</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold font-mono text-sm">
                QR
            </div>
        </div>

        <!-- NFC Taps -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Tap Kartu NFC</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalNfcScans) }}</h3>
                <span class="text-[11px] text-slate-400 mt-1 block">{{ $totalScans > 0 ? round(($totalNfcScans / $totalScans) * 100) : 0 }}% via tap contactless</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold font-mono text-sm">
                NFC
            </div>
        </div>

    </div>

    <!-- Chart & Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Scans Chart -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm lg:col-span-2 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Pertumbuhan Review 14 Hari Terakhir</h3>
                    <p class="text-xs text-slate-500">Pergerakan pelanggan yang scan QR atau tap NFC.</p>
                </div>
                <a href="{{ route('portal.analytics') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                    Analitik Lengkap &rarr;
                </a>
            </div>
            <div class="flex-1 min-h-[220px]">
                <canvas id="portalScansChart"></canvas>
            </div>
        </div>

        <!-- Devices Quick List -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Perangkat Saya</h3>
                    <a href="{{ route('portal.devices.index') }}" class="text-xs text-emerald-600 font-semibold hover:underline">Semua &rarr;</a>
                </div>

                <div class="space-y-3">
                    @php $sampleDevices = $businesses->flatMap->devices->take(4); @endphp
                    @forelse($sampleDevices as $dev)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-mono font-bold text-slate-900">{{ $dev->device_code }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ $dev->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-900">{{ number_format($dev->total_scans) }} scans</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 block mt-0.5">Aktif</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400">
                            Belum ada perangkat yang terhubung.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100">
                <a href="{{ route('portal.devices.index') }}" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    <span>Unduh Materi Cetak QR Code</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Recent Customer Redirects Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Aktivitas Ulasan Terbaru</h3>
                <p class="text-xs text-slate-500">Pelanggan yang baru saja dialihkan ke Google Review</p>
            </div>
            <a href="{{ route('portal.analytics') }}" class="text-xs font-semibold text-emerald-600 hover:underline">Semua Riwayat &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4">Perangkat</th>
                        <th class="py-3 px-4">Device Pelanggan</th>
                        <th class="py-3 px-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentScans as $scan)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-500">{{ $scan->scanned_at->diffForHumans() }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $scan->scan_type === 'nfc' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $scan->scan_type === 'nfc' ? 'Tap NFC' : 'Scan QR' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono font-semibold text-slate-900">{{ $scan->device?->device_code }} ({{ $scan->device?->name }})</td>
                            <td class="py-3 px-4">{{ $scan->device_type }} &bull; {{ $scan->platform }}</td>
                            <td class="py-3 px-4 text-right text-emerald-600 font-semibold">Berhasil ke Google Review ✓</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada pemindaian tercatat. Letakkan stand QR/NFC di meja atau kasir Anda!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('portalScansChart').getContext('2d');
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
