@extends('layouts.admin')

@section('title', 'Edit Perangkat ' . $device->device_code . ' - ReviewIn')
@section('page_title', 'Edit Data Perangkat: ' . $device->device_code)

@section('content')
<div class="max-w-xl mx-auto">
    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Perbarui Informasi Perangkat</h3>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Kode: {{ $device->device_code }}</p>
            </div>
            <a href="{{ route('admin.devices.show', $device) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900">
                &larr; Batal
            </a>
        </div>

        <form action="{{ route('admin.devices.update', $device) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama / Label Lokasi <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $device->name) }}" required
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Tipe</label>
                    <select id="type" name="type" class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                        <option value="qr_nfc" {{ $device->type === 'qr_nfc' ? 'selected' : '' }}>QR Code + NFC Tag</option>
                        <option value="qr_only" {{ $device->type === 'qr_only' ? 'selected' : '' }}>Hanya QR Code</option>
                        <option value="nfc_only" {{ $device->type === 'nfc_only' ? 'selected' : '' }}>Hanya Tag NFC</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                        <option value="unactivated" {{ $device->status === 'unactivated' ? 'selected' : '' }}>Unactivated</option>
                        <option value="active" {{ $device->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $device->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="blocked" {{ $device->status === 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="business_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Bisnis Terhubung</label>
                <select id="business_id" name="business_id" class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">-- Tidak Terhubung ke Bisnis (Unassigned) --</option>
                    @foreach($businesses as $b)
                        <option value="{{ $b->id }}" {{ old('business_id', $device->business_id) == $b->id ? 'selected' : '' }}>
                            {{ $b->name }} ({{ $b->category ?? 'Bisnis' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="google_review_url_override" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Google Review URL Override (Khusus Device Ini)
                </label>
                <input type="url" id="google_review_url_override" name="google_review_url_override" value="{{ old('google_review_url_override', $device->google_review_url_override) }}"
                       placeholder="Kosongkan untuk menggunakan URL default bisnis"
                       class="w-full rounded-xl border-slate-300 font-mono text-xs py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                <p class="text-[11px] text-slate-500 mt-1">Jika diisi, link ini akan mengabaikan URL utama bisnis.</p>
            </div>

            <div>
                <label for="notes" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Catatan</label>
                <textarea id="notes" name="notes" rows="2" class="w-full rounded-xl border-slate-300 text-sm py-2 px-3 focus:border-brand-500 focus:ring-brand-500">{{ old('notes', $device->notes) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.devices.show', $device) }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/30 text-xs transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
