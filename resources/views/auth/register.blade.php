@extends('layouts.app')

@section('title', 'Daftar Akun Pemilik Bisnis - ReviewIn')

@section('body')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-600/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 shadow-xl shadow-emerald-500/30 group-hover:scale-105 transition-transform font-black">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold text-white tracking-tight">ReviewIn</span>
            </a>
            <h2 class="mt-4 text-2xl font-bold tracking-tight text-white">Daftar Akun Pemilik Bisnis</h2>
            <p class="mt-1 text-sm text-slate-400">
                Mulai kelola perangkat QR Code & NFC Google Review toko Anda
            </p>
        </div>

        <div class="bg-white/95 backdrop-blur-xl py-8 px-6 shadow-2xl rounded-2xl sm:px-10 border border-white/20">
            @if(!empty($redirect))
                <div class="mb-5 p-3 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-2.5 text-xs text-amber-900 font-medium">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Setelah registrasi selesai, Anda akan langsung diarahkan untuk melanjutkan proses aktivasi perangkat.</span>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf
                @if(!empty($redirect))
                    <input type="hidden" name="redirect" value="{{ $redirect }}">
                @endif

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                           class="w-full rounded-xl shadow-sm text-sm py-2.5 px-3.5 {{ $errors->has('name') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-brand-500 focus:ring-brand-500' }}"
                           placeholder="Contoh: Budi Santoso">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="w-full rounded-xl shadow-sm text-sm py-2.5 px-3.5 {{ $errors->has('email') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-brand-500 focus:ring-brand-500' }}"
                           placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor WhatsApp / HP</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                           class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5"
                           placeholder="Contoh: 081234567890">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                        <input id="password" name="password" type="password" required
                               class="w-full rounded-xl shadow-sm text-sm py-2.5 px-3.5 {{ $errors->has('password') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-brand-500 focus:ring-brand-500' }}"
                               placeholder="Min. 6 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Ulangi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5"
                               placeholder="••••••••">
                    </div>
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror

                <button type="submit" class="w-full py-3 px-4 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/30 transition-all text-sm mt-2">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-5 text-center text-xs text-slate-600">
                Sudah memiliki akun? 
                <a href="{{ route('login', !empty($redirect) ? ['redirect' => $redirect] : []) }}" class="font-semibold text-emerald-600 hover:text-emerald-700 underline">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
