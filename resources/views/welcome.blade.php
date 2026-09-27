@extends('layouts.app')

@section('title', 'CreTech - Platform Pengelolaan QR Code & NFC Google Review Terpadu')

@section('body')
<div class="min-h-screen bg-slate-900 text-slate-100 flex flex-col selection:bg-brand-500 selection:text-white relative overflow-hidden">
    
    <!-- Background Gradient Grids -->
    <div class="absolute inset-0 bg-[radial-gradient(#312e81_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-600/25 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Navigation Header -->
    <header class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between border-b border-slate-800">
        <a href="{{ route('home') }}" class="flex items-center group py-2">
            <img src="{{ asset('assets/CreTechlogin.svg') }}" alt="CreTech" 
                 class="h-10 sm:h-11 w-auto max-w-[170px] sm:max-w-[200px] object-contain drop-shadow-md group-hover:scale-105 transition-transform">
        </a>

        <div class="flex items-center gap-3">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-600/30 transition-all">
                        Dashboard Admin &rarr;
                    </a>
                @else
                    <a href="{{ route('portal.dashboard') }}" class="px-4 py-2 text-sm font-semibold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-600/30 transition-all">
                        Dashboard Bisnis &rarr;
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-600/30 transition-all">
                    Daftar Bisnis
                </a>
            @endauth
        </div>
    </header>

    <!-- Hero Section -->
    <main class="relative z-10 flex-1 flex flex-col justify-center py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800/80 border border-slate-700 text-slate-300 text-xs font-medium mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Smart QR Code & NFC Google Review Management</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-tight">
                Tingkatkan Bintang 5 Bisnis Anda dengan <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-400 via-indigo-300 to-emerald-400">QR Code & NFC Cerdas</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                Sistem terpadu untuk administrator mencetak dan menyediakan perangkat QR & NFC, pemilik bisnis mengaktifkan secara mandiri, dan pelanggan langsung diarahkan ke ulasan Google secara instan.
            </p>

            <!-- Quick Action Portals -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-white bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-700 hover:to-indigo-700 shadow-xl shadow-brand-600/25 transition-all text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                    <span>Masuk ke Sistem</span>
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-slate-300 bg-slate-800/80 hover:bg-slate-700 hover:text-white border border-slate-700 text-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    <span>Daftarkan Bisnis Anda &rarr;</span>
                </a>
            </div>

            <!-- Workflow Visualizer -->
            <div class="mt-16 pt-12 border-t border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-widest text-brand-400 mb-8">Alur Kerja Sistem CreTech</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-left max-w-5xl mx-auto">
                    
                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-slate-700/60 relative">
                        <div class="w-8 h-8 rounded-lg bg-brand-900/60 text-brand-400 flex items-center justify-center font-bold text-sm mb-3">1</div>
                        <h4 class="font-bold text-white text-sm">Penyediaan Perangkat</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Admin mencetak kode unik (REV-XXXX), membuat QR Code SVG/PNG, dan memprogram tag NFC.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-amber-500/30 relative">
                        <div class="w-8 h-8 rounded-lg bg-amber-900/60 text-amber-400 flex items-center justify-center font-bold text-sm mb-3">2</div>
                        <h4 class="font-bold text-amber-300 text-sm">Aktivasi Mandiri</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pemilik bisnis menerima kartu/stand dan melakukan scan pertama kali untuk memulai proses aktivasi.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-slate-700/60 relative">
                        <div class="w-8 h-8 rounded-lg bg-indigo-900/60 text-indigo-400 flex items-center justify-center font-bold text-sm mb-3">3</div>
                        <h4 class="font-bold text-white text-sm">Hubungkan Bisnis</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pemilik bisnis mengaitkan perangkat ke profil usaha dan memasukkan tautan Google Review resmi.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-emerald-500/30 relative">
                        <div class="w-8 h-8 rounded-lg bg-emerald-900/60 text-emerald-400 flex items-center justify-center font-bold text-sm mb-3">4</div>
                        <h4 class="font-bold text-emerald-300 text-sm">Ulasan Pelanggan</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pelanggan cukup tap NFC atau scan QR code di meja untuk langsung memberikan ulasan bintang 5.</p>
                    </div>

                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-20 max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/10 text-brand-400 text-xs font-semibold mb-2">
                        Keunggulan Platform
                    </span>
                    <h3 class="text-2xl font-bold text-white">Solusi Cerdas Pengumpulan Ulasan Bisnis</h3>
                    <p class="text-sm text-slate-400 mt-1 max-w-xl mx-auto">Dirancang untuk mempermudah restoran, kafe, klinik, hotel, dan usaha ritel mendapatkan ulasan positif Google secara nyata.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-left">
                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-6 border border-slate-700/60 hover:border-brand-500/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-brand-600/20 text-brand-400 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        <h4 class="font-bold text-white text-base">QR & NFC Terpadu</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Mendukung tap nirkabel NFC dan scan QR Code resolusi tinggi untuk seluruh tipe ponsel pintar.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-6 border border-slate-700/60 hover:border-emerald-500/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h4 class="font-bold text-white text-base">Redirect 302 Instan</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Pengalihan langsung ke halaman tulis review Google tanpa jeda dan tanpa aplikasi perantara.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-6 border border-slate-700/60 hover:border-amber-500/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-amber-600/20 text-amber-400 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                        <h4 class="font-bold text-white text-base">Analitik & Telemetri</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Pantau volume scan harian, perangkat paling aktif, dan statistik ulasan secara transparan.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-6 border border-slate-700/60 hover:border-indigo-500/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <h4 class="font-bold text-white text-base">Kontrol Penuh</h4>
                        <p class="text-xs text-slate-400 mt-2 leading-relaxed">Admin dan pemilik dapat mengubah target URL ulasan atau menonaktifkan perangkat kapan saja.</p>
                    </div>
                </div>

                <!-- Call to Action Banner -->
                <div class="mt-14 p-8 rounded-3xl bg-gradient-to-r from-brand-900/60 via-indigo-900/60 to-slate-900/80 border border-brand-500/30 text-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-xl sm:text-2xl font-bold text-white">Siap Mengembangkan Reputasi Bintang 5 Bisnis Anda?</h3>
                        <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-xl mx-auto">Daftarkan bisnis Anda sekarang dan hubungkan perangkat QR & NFC Anda untuk mendapatkan lebih banyak ulasan autentik dari pelanggan.</p>
                        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-500 transition-all text-xs sm:text-sm shadow-lg shadow-brand-600/30">
                                Mulai Sekarang &rarr;
                            </a>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-800 transition-all text-xs sm:text-sm border border-slate-700">
                                Masuk ke Portal
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-800 py-6 text-center text-xs text-slate-500">
        CreTech &copy; {{ date('Y') }} - Sistem Pengelolaan QR Code & NFC Google Review Bisnis.
    </footer>
</div>
@endsection
