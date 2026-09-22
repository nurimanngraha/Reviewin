@extends('layouts.app')

@section('title', 'Masuk ke Akun - CreTech')

@section('body')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Subtle Background Glows -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-600/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-white p-1.5 flex items-center justify-center shadow-xl shadow-brand-500/30 group-hover:scale-105 transition-transform shrink-0">
                    <img src="{{ asset('assets/CreTechlogopersegi.svg') }}" alt="CreTech Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-3xl font-black text-white tracking-tight">Cre<span class="text-emerald-400">Tech</span></span>
            </a>
            <h2 class="mt-4 text-2xl font-bold tracking-tight text-white">Masuk ke Akun Anda</h2>
            <p class="mt-1 text-sm text-slate-400">
                Akses sistem pengelolaan QR Code & NFC Google Review
            </p>
        </div>

        <div class="bg-white/95 backdrop-blur-xl py-8 px-6 shadow-2xl rounded-2xl sm:px-10 border border-white/20">
            @if(!empty($redirect))
                <div class="mb-5 p-3 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-2.5 text-xs text-amber-900 font-medium">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Setelah login berhasil, Anda akan langsung diarahkan untuk melanjutkan proses aktivasi perangkat.</span>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf
                @if(!empty($redirect))
                    <input type="hidden" name="redirect" value="{{ $redirect }}">
                @endif

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
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full rounded-xl shadow-sm text-sm py-2.5 px-3.5 {{ $errors->has('password') ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-brand-500 focus:ring-brand-500' }}"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl font-semibold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 shadow-lg shadow-brand-600/30 transition-all text-sm mt-2">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Credentials Fillers -->
            <div class="mt-6 pt-5 border-t border-slate-200">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-center mb-3">Akun Demo Cepat</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" 
                            onclick="document.getElementById('email').value='admin@reviewin.test'; document.getElementById('password').value='password';"
                            class="py-2 px-3 text-xs font-medium rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors text-center border border-slate-200">
                        🔑 Admin Demo
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('email').value='owner@reviewin.test'; document.getElementById('password').value='password';"
                            class="py-2 px-3 text-xs font-medium rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 transition-colors text-center border border-emerald-200">
                        🏪 Pemilik Bisnis
                    </button>
                </div>
            </div>

            <div class="mt-5 text-center text-xs text-slate-600">
                Belum punya akun Pemilik Bisnis? 
                <a href="{{ route('register', !empty($redirect) ? ['redirect' => $redirect] : []) }}" class="font-semibold text-brand-600 hover:text-brand-700 underline">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
