@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru - ReviewIn')
@section('page_title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <div class="pb-4 mb-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Form Pengguna Baru</h3>
            <p class="text-xs text-slate-500 mt-0.5">Buat akun untuk Administrator atau Pemilik Bisnis baru.</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email <span class="text-rose-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Role Akun <span class="text-rose-500">*</span></label>
                    <select id="role" name="role" required class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                        <option value="business_owner" {{ old('role') === 'business_owner' ? 'selected' : '' }}>Pemilik Bisnis (Default)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator Sistem</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password Awal <span class="text-rose-500">*</span></label>
                <input type="password" id="password" name="password" required
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500"
                       placeholder="Min. 6 karakter">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/30 text-xs transition-all">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
