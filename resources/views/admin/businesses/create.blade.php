@extends('layouts.admin')

@section('title', 'Daftarkan Bisnis Baru - ReviewIn')
@section('page_title', 'Daftarkan Bisnis Baru')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <div class="pb-4 mb-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Informasi Bisnis Baru</h3>
            <p class="text-xs text-slate-500 mt-0.5">Daftarkan profil bisnis dan link tujuan Google Review.</p>
        </div>

        <form action="{{ route('admin.businesses.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="user_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Pemilik Bisnis (Akun User) <span class="text-rose-500">*</span>
                </label>
                <select id="user_id" name="user_id" required class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">-- Pilih Akun Pemilik --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Bisnis / Toko / Cabang <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="Contoh: Kopi Kenangan Senopati"
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}"
                           placeholder="Contoh: Coffee Shop, Resto, Klinik"
                           class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor Telepon / WA</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           placeholder="08123456789"
                           class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="2" placeholder="Jl. Senopati No. 12, Kebayoran Baru..."
                          class="w-full rounded-xl border-slate-300 text-sm py-2 px-3 focus:border-brand-500 focus:ring-brand-500">{{ old('address') }}</textarea>
            </div>

            <div>
                <label for="google_review_url" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Link Google Review Bisnis <span class="text-rose-500">*</span>
                </label>
                <input type="url" id="google_review_url" name="google_review_url" value="{{ old('google_review_url') }}" required
                       placeholder="https://g.page/r/xxxx/review atau https://maps.app.goo.gl/xxxx"
                       class="w-full rounded-xl border-slate-300 font-mono text-xs py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('google_review_url')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-slate-500 mt-1">Semua perangkat yang terhubung ke bisnis ini akan otomatis mengarah ke link ini.</p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.businesses.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/30 text-xs transition-all">
                    Daftarkan Bisnis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
