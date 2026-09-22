@extends('layouts.admin')

@section('title', 'Buat Perangkat Baru - ReviewIn')
@section('page_title', 'Buat Perangkat QR & NFC Baru')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ mode: 'bulk' }">

    <!-- Mode Toggle Tabs -->
    <div class="flex items-center justify-center p-1 bg-slate-200/80 rounded-2xl mb-6 text-xs font-semibold max-w-md mx-auto">
        <button type="button" @click="mode = 'bulk'" 
                :class="mode === 'bulk' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 py-2 px-4 rounded-xl transition-all">
            ⚡ Pembuatan Massal (Bulk Generator)
        </button>
        <button type="button" @click="mode = 'single'" 
                :class="mode === 'single' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                class="flex-1 py-2 px-4 rounded-xl transition-all">
            🛠 Buat 1 Perangkat Satuan
        </button>
    </div>

    <!-- Bulk Form -->
    <div x-show="mode === 'bulk'" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <div class="mb-6 pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Pembuatan Perangkat Secara Massal</h3>
            <p class="text-xs text-slate-500 mt-1">
                Sangat cocok untuk administrator mencetak puluhan/ratusan kartu NFC atau stiker QR Code sekaligus dengan status <strong>unactivated</strong> siap dibagikan ke pemilik bisnis.
            </p>
        </div>

        <form action="{{ route('admin.devices.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="is_bulk" value="1">

            <div>
                <label for="count" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Jumlah Perangkat yang Dibuat <span class="text-rose-500">*</span>
                </label>
                <input type="number" id="count" name="count" min="1" max="100" value="{{ old('count', 10) }}" required
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                <p class="text-[11px] text-slate-500 mt-1">Maksimal 100 perangkat dalam sekali proses pembuatan.</p>
                @error('count')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="prefix" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Prefix Kode Unik
                    </label>
                    <input type="text" id="prefix" name="prefix" value="{{ old('prefix', 'REV') }}" maxlength="10"
                           class="w-full rounded-xl border-slate-300 font-mono uppercase text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500"
                           placeholder="REV">
                    <p class="text-[11px] text-slate-500 mt-1">Format hasil: <code class="font-bold">REV-XXXXXX</code></p>
                </div>
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Tipe Perangkat
                    </label>
                    <select id="type" name="type" class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                        <option value="qr_nfc">QR Code + NFC Tag (Standar)</option>
                        <option value="qr_only">Hanya QR Code</option>
                        <option value="nfc_only">Hanya Tag NFC</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="device_name_prefix" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Prefix Label / Nama Perangkat
                </label>
                <input type="text" id="device_name_prefix" name="device_name_prefix" value="{{ old('device_name_prefix', 'Stand Akrilik') }}"
                       placeholder="Contoh: Kartu Meja, Stand Akrilik, Sticker Kasir"
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                <p class="text-[11px] text-slate-500 mt-1">Akan diberi nomor urut otomatis (misal: Stand Akrilik #1, #2, dll).</p>
            </div>

            <div>
                <label for="notes" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Catatan Batch (Opsional)
                </label>
                <textarea id="notes" name="notes" rows="2" placeholder="Contoh: Batch Cetak September 2026 - Vendor Akrilik"
                          class="w-full rounded-xl border-slate-300 text-sm py-2 px-3 focus:border-brand-500 focus:ring-brand-500">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.devices.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/30 text-xs transition-all">
                    Buat Perangkat Massal Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- Single Device Form -->
    <div x-show="mode === 'single'" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <div class="mb-6 pb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Buat Perangkat Satuan</h3>
            <p class="text-xs text-slate-500 mt-1">
                Buat satu perangkat khusus dan langsung hubungkan ke bisnis jika diinginkan.
            </p>
        </div>

        <form action="{{ route('admin.devices.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="device_code" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Kode Perangkat (Opsional)
                    </label>
                    <input type="text" id="device_code" name="device_code" value="{{ old('device_code') }}"
                           placeholder="Auto-generate"
                           class="w-full rounded-xl border-slate-300 font-mono uppercase text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                    @error('device_code')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="activation_code" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Kode Kartu (Activation Code)
                    </label>
                    <input type="text" id="activation_code" name="activation_code" value="{{ old('activation_code') }}"
                           placeholder="Auto-generate (ACT-XXXXXX)"
                           class="w-full rounded-xl border-slate-300 font-mono uppercase text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                    @error('activation_code')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="type_single" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Tipe Perangkat
                    </label>
                    <select id="type_single" name="type" class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                        <option value="qr_nfc">QR Code + NFC Tag</option>
                        <option value="qr_only">Hanya QR Code</option>
                        <option value="nfc_only">Hanya NFC Tag</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama / Label Lokasi <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', 'Stand Kasir 01') }}" required
                       placeholder="Contoh: Meja Kasir Utama, Meja 5 Outdoor"
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="business_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Hubungkan ke Bisnis (Opsional)
                    </label>
                    <select id="business_id" name="business_id" class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                        <option value="">-- Belum Dihubungkan (Aktivasi Nanti) --</option>
                        @foreach($businesses as $b)
                            <option value="{{ $b->id }}" {{ old('business_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-500 mt-1">Bisa dikosongkan agar diaktivasi sendiri oleh pemilik bisnis.</p>
                </div>
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Status Awal
                    </label>
                    <select id="status" name="status" class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                        <option value="unactivated" {{ old('status') === 'unactivated' ? 'selected' : '' }}>Unactivated (Belum Aktif)</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active (Langsung Aktif)</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Nonaktif)</option>
                        <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>Blocked (Diblokir)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="google_review_url_override" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Google Review URL Khusus / Override (Opsional)
                </label>
                <input type="url" id="google_review_url_override" name="google_review_url_override" value="{{ old('google_review_url_override') }}"
                       placeholder="Kosongkan jika menggunakan URL bawaan bisnis"
                       class="w-full rounded-xl border-slate-300 font-mono text-xs py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.devices.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/30 text-xs transition-all">
                    Simpan Perangkat
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
