@extends('layouts.admin')

@section('title', 'Edit Bisnis ' . $business->name . ' - ReviewIn')
@section('page_title', 'Edit Data Bisnis: ' . $business->name)

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Perbarui Data Bisnis</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ $business->name }}</p>
            </div>
            <a href="{{ route('admin.businesses.show', $business) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900">
                &larr; Batal
            </a>
        </div>

        <form action="{{ route('admin.businesses.update', $business) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="user_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Pemilik Bisnis <span class="text-rose-500">*</span>
                </label>
                <select id="user_id" name="user_id" required class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id', $business->user_id) == $u->id ? 'selected' : '' }}>
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
                    Nama Bisnis / Toko <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $business->name) }}" required
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $business->category) }}"
                           class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $business->phone) }}"
                           class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="2" class="w-full rounded-xl border-slate-300 text-sm py-2 px-3 focus:border-brand-500 focus:ring-brand-500">{{ old('address', $business->address) }}</textarea>
            </div>

            <div>
                <label for="google_review_url" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Link Google Review <span class="text-rose-500">*</span>
                </label>
                <input type="url" id="google_review_url" name="google_review_url" value="{{ old('google_review_url', $business->google_review_url) }}" required
                       class="w-full rounded-xl border-slate-300 font-mono text-xs py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('google_review_url')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.businesses.show', $business) }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
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
