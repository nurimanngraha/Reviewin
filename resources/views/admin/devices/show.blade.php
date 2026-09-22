@extends('layouts.admin')

@section('title', 'Detail Perangkat ' . $device->device_code . ' - CreTech')
@section('page_title', 'Detail Perangkat: ' . $device->device_code)

@section('content')
<div class="space-y-6" x-data="{ resetModal: false, copiedNfc: false, copiedQr: false, copiedAct: false }">

    <!-- Top Navigation Breadcrumbs -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.devices.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            <span>Kembali ke Daftar Perangkat</span>
        </a>

        <!-- Quick Status Switcher -->
        <form action="{{ route('admin.devices.status', $device) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <label class="text-xs font-semibold text-slate-500">Ubah Status:</label>
            <select name="status" onchange="this.form.submit()" 
                    class="rounded-xl border-slate-300 text-xs py-1.5 px-3 font-semibold focus:border-brand-500 focus:ring-brand-500
                    {{ $device->status === 'active' ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : '' }}
                    {{ $device->status === 'unactivated' ? 'text-amber-700 bg-amber-50 border-amber-200' : '' }}
                    {{ $device->status === 'inactive' ? 'text-slate-700 bg-slate-100 border-slate-200' : '' }}
                    {{ $device->status === 'blocked' ? 'text-rose-700 bg-rose-50 border-rose-200' : '' }}">
                <option value="unactivated" {{ $device->status === 'unactivated' ? 'selected' : '' }}>Unactivated</option>
                <option value="active" {{ $device->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $device->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="blocked" {{ $device->status === 'blocked' ? 'selected' : '' }}>Blocked</option>
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: QR Code & NFC Box -->
        <div class="space-y-6">
            
            <!-- QR Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4
                      {{ $device->status === 'active' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : '' }}
                      {{ $device->status === 'unactivated' ? 'bg-amber-50 text-amber-800 border border-amber-200' : '' }}
                      {{ $device->status === 'inactive' ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}
                      {{ $device->status === 'blocked' ? 'bg-rose-50 text-rose-800 border border-rose-200' : '' }}">
                    Status: {{ ucfirst($device->status) }}
                </span>

                <!-- QR Code SVG Container -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl inline-block shadow-inner mb-4">
                    <div class="w-64 h-64 flex items-center justify-center mx-auto">
                        {!! $qrSvg !!}
                    </div>
                </div>

                <h3 class="font-mono font-extrabold text-lg text-slate-900 tracking-tight">{{ $device->device_code }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ $device->name }}</p>

                <!-- Download Buttons -->
                <div class="grid grid-cols-2 gap-2 mt-5">
                    <a href="{{ route('admin.devices.download.svg', $device) }}" 
                       class="py-2.5 px-3 rounded-xl font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 text-xs transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>Unduh SVG</span>
                    </a>
                    <a href="{{ route('admin.devices.download.png', $device) }}" 
                       class="py-2.5 px-3 rounded-xl font-semibold text-white bg-slate-900 hover:bg-slate-800 text-xs transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>Unduh PNG</span>
                    </a>
                </div>
            </div>

            <!-- Activation Code Card for Physical Packaging -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-900 flex items-center gap-1.5">
                        <i class="fas fa-key text-amber-600"></i> Kode Kartu (Activation Code)
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-200/70 text-amber-900">Verifikasi Fisik</span>
                </div>
                <p class="text-xs text-amber-800 leading-relaxed mb-3">
                    Kode ini dimasukkan oleh Pemilik Bisnis saat aktivasi kartu. Cetak kode ini pada kemasan perangkat.
                </p>
                <div class="flex items-center gap-2 bg-white border border-amber-300 rounded-xl p-2.5">
                    <span class="font-mono font-black text-lg text-slate-900 tracking-wider flex-1 text-center select-all">
                        {{ $device->activation_code ?? 'BELUM DIATUR' }}
                    </span>
                    <button type="button" 
                            @click="navigator.clipboard.writeText('{{ $device->activation_code }}'); copiedAct = true; setTimeout(() => copiedAct = false, 2000)"
                            class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded-lg text-xs font-bold transition-colors">
                        <span x-text="copiedAct ? 'Tersalin!' : 'Salin'"></span>
                    </button>
                </div>
            </div>

            <!-- NFC Configuration Box -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                        <span>URL Tag NFC</span>
                    </h4>
                    <span class="text-[10px] text-slate-400">NTAG213/215</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tulis URL berikut ke dalam chip tag NFC kartu/stand akrilik menggunakan aplikasi NFC Writer (seperti NFC Tools di HP).
                </p>
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

            <!-- Test Dynamic Redirect -->
            <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-2xl p-5 text-white shadow-md">
                <h4 class="text-sm font-bold">Uji Coba Live Pemindaian</h4>
                <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                    Klik link di bawah ini untuk melihat perilaku sistem saat perangkat ini dipindai oleh pengguna.
                </p>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="{{ route('device.redirect', $device->device_code) }}" target="_blank"
                       class="py-2 px-3 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs text-center transition-colors flex items-center justify-center gap-1.5">
                        <span>Akses URL /r/{{ $device->device_code }} &rarr;</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Column: Details, Telemetry, and History -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Information Details -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">{{ $device->name }}</h3>
                        <p class="text-xs text-slate-500 font-mono mt-0.5">Device Code: {{ $device->device_code }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.devices.edit', $device) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                            Edit Perangkat
                        </a>
                        @if($device->status !== 'unactivated')
                            <button type="button" @click="resetModal = true" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors">
                                Reset ke Unactivated
                            </button>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium block">Bisnis Terhubung:</span>
                        @if($device->business)
                            <a href="{{ route('admin.businesses.show', $device->business) }}" class="font-bold text-brand-600 hover:underline text-sm mt-0.5 block">
                                {{ $device->business->name }}
                            </a>
                            <span class="text-[11px] text-slate-500">Pemilik: {{ $device->business->user?->name }} ({{ $device->business->user?->phone ?? '-' }})</span>
                        @else
                            <span class="font-bold text-amber-600 mt-0.5 block">Belum Terhubung (Menunggu Aktivasi Pertama)</span>
                        @endif
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-500 font-medium block">Waktu Aktivasi:</span>
                        <span class="font-bold text-slate-900 text-sm mt-0.5 block">
                            {{ $device->activated_at ? $device->activated_at->translatedFormat('d F Y, H:i') : 'Belum Pernah Diaktivasi' }}
                        </span>
                        <span class="text-[11px] text-slate-500">Dibuat: {{ $device->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>

                <!-- Google Review Link -->
                <div class="mt-4 p-3.5 rounded-xl bg-indigo-50/60 border border-indigo-100 text-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-indigo-950">Target Link Google Review Aktif:</span>
                        @if($device->effective_review_url)
                            <a href="{{ $device->effective_review_url }}" target="_blank" class="text-brand-600 font-bold hover:underline flex items-center gap-1">
                                <span>Uji Link Google Review</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>
                        @endif
                    </div>
                    <p class="font-mono text-[11px] text-slate-700 break-all bg-white p-2 rounded-lg border border-indigo-100">
                        {{ $device->effective_review_url ?: 'Belum diatur (Perangkat masih unactivated atau bisnis belum mengisi link)' }}
                    </p>
                </div>
            </div>

            <!-- Usage Telemetry Counters -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm text-center">
                    <span class="text-slate-500 text-xs block">Total Scans</span>
                    <span class="text-2xl font-extrabold text-slate-900 mt-1 block">{{ number_format($device->total_scans) }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm text-center">
                    <span class="text-brand-600 text-xs font-semibold block">QR Scans</span>
                    <span class="text-2xl font-extrabold text-brand-600 mt-1 block">{{ number_format($device->total_qr_scans) }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm text-center">
                    <span class="text-emerald-600 text-xs font-semibold block">NFC Taps</span>
                    <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">{{ number_format($device->total_nfc_scans) }}</span>
                </div>
            </div>

            <!-- Activation History -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Riwayat Aktivasi Perangkat</h4>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($device->activations as $act)
                        <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="font-bold text-slate-900">Diaktivasi oleh: {{ $act->user?->name }} ({{ $act->business?->name }})</p>
                                <p class="text-[11px] text-slate-500">{{ $act->notes ?? 'Aktivasi mandiri melalui scan pertama' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-500 text-[11px] block">{{ $act->activated_at->translatedFormat('d M Y, H:i') }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $act->ip_address }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400">Belum ada riwayat aktivasi untuk perangkat ini.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Scan Logs on this Device -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Log Pemindaian Terakhir</h4>
                    <span class="text-[11px] text-slate-400">Menampilkan 20 scan terakhir</span>
                </div>
                <div class="divide-y divide-slate-100 text-xs overflow-x-auto">
                    @forelse($device->scans as $scan)
                        <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $scan->scan_type === 'nfc' ? 'bg-emerald-100 text-emerald-800' : 'bg-brand-100 text-brand-800' }}">
                                    {{ $scan->scan_type }}
                                </span>
                                <div>
                                    <span class="font-semibold text-slate-800">{{ $scan->device_type }} &bull; {{ $scan->platform }} ({{ $scan->browser }})</span>
                                    <span class="text-[10px] text-slate-400 font-mono block">{{ $scan->ip_address }}</span>
                                </div>
                            </div>
                            <span class="text-slate-500 text-[11px]">{{ $scan->scanned_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400">Belum ada aktivitas pemindaian tercatat.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Reset Modal -->
    <div x-show="resetModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="resetModal = false" class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl border border-slate-100 text-left">
            <h3 class="text-base font-bold text-slate-900">Reset Perangkat Ini?</h3>
            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                Status perangkat akan kembali menjadi <strong>unactivated</strong> dan terputus dari bisnis saat ini.
            </p>
            <form action="{{ route('admin.devices.reset', $device) }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <input type="text" name="reason" placeholder="Alasan reset..." class="w-full rounded-xl border-slate-300 text-xs py-2 px-3">
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="resetModal = false" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 shadow">Ya, Reset Sekarang</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
