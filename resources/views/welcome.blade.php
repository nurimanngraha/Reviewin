@extends('layouts.app')

@section('title', 'ReviewIn - Platform Pengelolaan QR Code & NFC Google Review Terpadu')

@section('body')
<div class="min-h-screen bg-slate-900 text-slate-100 flex flex-col selection:bg-brand-500 selection:text-white relative overflow-hidden">
    
    <!-- Background Gradient Grids -->
    <div class="absolute inset-0 bg-[radial-gradient(#312e81_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-600/25 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Navigation Header -->
    <header class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between border-b border-slate-800">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-400 flex items-center justify-center text-white shadow-lg shadow-brand-500/30 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-white">ReviewIn</span>
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
                    <span>Masuk ke Sistem (Admin / Bisnis)</span>
                </a>
                <a href="#simulator" class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-slate-300 bg-slate-800/80 hover:bg-slate-700 hover:text-white border border-slate-700 text-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Uji Coba Alur Scan / Tap Live</span>
                </a>
            </div>

            <!-- Workflow Visualizer -->
            <div class="mt-16 pt-12 border-t border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-widest text-brand-400 mb-8">Alur Lifecycle Perangkat</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-left max-w-5xl mx-auto">
                    
                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-slate-700/60 relative">
                        <div class="w-8 h-8 rounded-lg bg-brand-900/60 text-brand-400 flex items-center justify-center font-bold text-sm mb-3">1</div>
                        <h4 class="font-bold text-white text-sm">Admin Sediakan Perangkat</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Admin membuat kode unik (REV-XXXX), cetak QR Code SVG/PNG, dan sediakan tag NFC berstatus unactivated.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-amber-500/30 relative">
                        <div class="w-8 h-8 rounded-lg bg-amber-900/60 text-amber-400 flex items-center justify-center font-bold text-sm mb-3">2</div>
                        <h4 class="font-bold text-amber-300 text-sm">Scan Pertama: Aktivasi</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pemilik bisnis menerima kartu & scan pertama kali. Sistem mendeteksi device belum aktif lalu buka halaman aktivasi.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-slate-700/60 relative">
                        <div class="w-8 h-8 rounded-lg bg-indigo-900/60 text-indigo-400 flex items-center justify-center font-bold text-sm mb-3">3</div>
                        <h4 class="font-bold text-white text-sm">Pemilik Bisnis Login & Ikat</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pemilik login, konfirmasi bisnis, dan masukkan link Google Review. Status berubah menjadi active.</p>
                    </div>

                    <div class="bg-slate-800/60 backdrop-blur rounded-2xl p-5 border border-emerald-500/30 relative">
                        <div class="w-8 h-8 rounded-lg bg-emerald-900/60 text-emerald-400 flex items-center justify-center font-bold text-sm mb-3">4</div>
                        <h4 class="font-bold text-emerald-300 text-sm">Scan Pelanggan: Redirect</h4>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Scan berikutnya langsung dicatat telemetrinya dan redirect 302 seketika ke halaman ulasan Google!</p>
                    </div>

                </div>
            </div>

            <!-- Live Simulator Interactive Sandbox -->
            <div id="simulator" class="mt-20 max-w-4xl mx-auto bg-slate-950/80 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-2xl text-left">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-500/10 text-brand-400 text-xs font-semibold mb-2">
                            Interactive Sandbox
                        </span>
                        <h3 class="text-xl font-bold text-white">Simulator Akses Dynamic Endpoint /r/{device_code}</h3>
                        <p class="text-xs text-slate-400 mt-1">Uji coba langsung respons sistem terhadap berbagai kondisi status perangkat fisik.</p>
                    </div>
                    <span class="text-xs text-slate-500 font-mono">Dynamic URL: /r/{code}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    
                    <!-- Demo Card 1: Unactivated -->
                    <div class="bg-slate-900 rounded-2xl p-5 border border-amber-500/30 hover:border-amber-500/60 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-mono text-xs font-bold text-white bg-slate-800 px-2 py-1 rounded">REV-DEMO02</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">unactivated</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-200">Perangkat Baru (Belum Aktif)</h4>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Ketika di-scan/tap pertama kali, sistem mengenali bahwa device belum aktif dan mengarahkan ke halaman aktivasi.</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <a href="{{ url('/r/REV-DEMO02') }}" target="_blank" class="w-full py-2 px-3 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 text-xs font-semibold text-center border border-amber-500/30 transition-all flex items-center justify-center gap-1.5">
                                <span>Simulasikan Scan Pertama &rarr;</span>
                            </a>
                        </div>
                    </div>

                    <!-- Demo Card 2: Active QR -->
                    <div class="bg-slate-900 rounded-2xl p-5 border border-emerald-500/30 hover:border-emerald-500/60 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-mono text-xs font-bold text-white bg-slate-800 px-2 py-1 rounded">REV-DEMO01</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">active</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-200">Pelanggan Scan QR Code</h4>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Perangkat sudah aktif terhubung ke Kopi Kenangan. Sistem mencatat telemetri scan dan redirect 302 ke Google Review.</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <a href="{{ url('/r/REV-DEMO01?t=qr') }}" target="_blank" class="w-full py-2 px-3 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 text-xs font-semibold text-center border border-emerald-500/30 transition-all flex items-center justify-center gap-1.5">
                                <span>Simulasikan Scan QR Pelanggan &rarr;</span>
                            </a>
                        </div>
                    </div>

                    <!-- Demo Card 3: Active NFC -->
                    <div class="bg-slate-900 rounded-2xl p-5 border border-indigo-500/30 hover:border-indigo-500/60 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-mono text-xs font-bold text-white bg-slate-800 px-2 py-1 rounded">REV-DEMO01</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">NFC Tap (?t=nfc)</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-200">Pelanggan Tap Kartu NFC</h4>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Kartu NFC yang ditempelkan HP pelanggan. Menggunakan URL yang sama dengan tag `?t=nfc` untuk membedakan telemetri.</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <a href="{{ url('/r/REV-DEMO01?t=nfc') }}" target="_blank" class="w-full py-2 px-3 rounded-xl bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-300 text-xs font-semibold text-center border border-indigo-500/30 transition-all flex items-center justify-center gap-1.5">
                                <span>Simulasikan Tap NFC Pelanggan &rarr;</span>
                            </a>
                        </div>
                    </div>

                    <!-- Demo Card 4: Inactive / Blocked -->
                    <div class="bg-slate-900 rounded-2xl p-5 border border-rose-500/30 hover:border-rose-500/60 transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-mono text-xs font-bold text-white bg-slate-800 px-2 py-1 rounded">REV-DEMO03</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">inactive</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-200">Perangkat Nonaktif / Diblokir</h4>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Jika perangkat dinonaktifkan atau diblokir oleh Admin, pelanggan akan melihat halaman peringatan ramah.</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <a href="{{ url('/r/REV-DEMO03') }}" target="_blank" class="w-full py-2 px-3 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 text-xs font-semibold text-center border border-rose-500/30 transition-all flex items-center justify-center gap-1.5">
                                <span>Simulasikan Akses Nonaktif &rarr;</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Demo Credentials Helper Card -->
            <div class="mt-10 max-w-xl mx-auto p-4 rounded-2xl bg-slate-800/50 border border-slate-700/60 text-xs text-slate-400">
                <span class="font-bold text-white block mb-1">Informasi Akun Demo Bawaan:</span>
                <div class="grid grid-cols-2 gap-3 mt-2 text-left">
                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800">
                        <span class="text-brand-400 font-bold block">Administrator</span>
                        <span>Email: <code class="text-white">admin@reviewin.test</code></span><br>
                        <span>Password: <code class="text-white">password</code></span>
                    </div>
                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800">
                        <span class="text-emerald-400 font-bold block">Pemilik Bisnis</span>
                        <span>Email: <code class="text-white">owner@reviewin.test</code></span><br>
                        <span>Password: <code class="text-white">password</code></span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-800 py-6 text-center text-xs text-slate-500">
        ReviewIn &copy; {{ date('Y') }} - Sistem Pengelolaan QR Code & NFC Google Review. Built with Laravel 11, Tailwind CSS, Blade & Alpine.js.
    </footer>
</div>
@endsection
