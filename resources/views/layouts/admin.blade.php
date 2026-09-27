@extends('layouts.app')

@php
    // =========================================================================
    // PENGATURAN UKURAN LOGO NAVBAR (PERSENTASE)
    // -------------------------------------------------------------------------
    // Ubah persentase di bawah ini untuk mengatur ukuran logo di sidebar:
    // Contoh: '70%', '80%', '85%', '90%', '95%', '100%'
    // =========================================================================
    $navbarLogoWidth = '85%';
@endphp

@section('body')
<div x-data="{ sidebarOpen: false }" class="h-screen bg-slate-100 flex overflow-hidden">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Sidebar Container -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-y-0 shrink-0 flex flex-col shadow-2xl h-screen">
        
        <!-- Brand Logo -->
        <div class="h-16 py-3 px-4 flex items-center justify-between border-b border-slate-800 bg-slate-950 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center group flex-1 overflow-hidden">
                <img src="{{ asset('assets/CreTechNavbarAdmin.svg') }}" 
                     alt="CreTech Admin" 
                     width="267"
                     height="110"
                     loading="eager"
                     fetchpriority="high"
                     style="width: {{ $navbarLogoWidth }}; max-width: 100%; height: auto; aspect-ratio: 267/110;" 
                     class="object-contain transition-transform duration-200 group-hover:scale-105">
            </a>
            <button @click="sidebarOpen = false" 
                    type="button"
                    aria-label="Tutup Menu"
                    class="lg:hidden p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg shrink-0 ml-2 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Navigasi Utama</div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span>Dashboard</span>
            </a>

            <!-- Devices -->
            <a href="{{ route('admin.devices.index') }}" 
               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.devices*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    <span>Perangkat QR & NFC</span>
                </div>
            </a>

            <!-- Businesses -->
            <a href="{{ route('admin.businesses.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.businesses*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span>Daftar Bisnis</span>
            </a>

            <!-- Telemetry / Scan Logs -->
            <a href="{{ route('admin.scans.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.scans*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <span>Log Scan & Tap</span>
            </a>

            <div class="pt-4 px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Pengaturan Sistem</div>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.users*') ? 'bg-brand-600 text-white shadow-lg shadow-brand-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span>Kelola Pengguna</span>
            </a>

            <!-- Public View / Test -->
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                <span>Lihat Beranda Publik</span>
            </a>
        </nav>

        <!-- Sidebar Footer / User Profile -->
        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-400 to-orange-500 flex items-center justify-center text-slate-950 font-bold text-sm shadow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">Administrator</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <!-- Topbar Header -->
        <header class="h-14 sm:h-16 shrink-0 bg-white/95 backdrop-blur-sm border-b border-slate-200 flex items-center justify-between px-3 sm:px-6 lg:px-8 shadow-xs z-10 sticky top-0">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1 mr-2 sm:mr-4">
                <button @click="sidebarOpen = true" 
                        type="button"
                        aria-label="Buka Menu"
                        class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-brand-500/20 shrink-0 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 truncate leading-snug" title="@yield('page_title', 'Admin Panel')">
                        @yield('page_title', 'Admin Panel')
                    </h1>
                </div>
            </div>

            <!-- Topbar Actions -->
            <div class="flex items-center gap-2 shrink-0">
                @hasSection('topbar_actions')
                    @yield('topbar_actions')
                @else
                    <a href="{{ route('admin.devices.create') }}" 
                       class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 py-1.5 sm:px-3.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-xl bg-brand-600 text-white hover:bg-brand-700 shadow-sm shadow-brand-600/20 transition-all hover:shadow-md hover:scale-[1.02] active:scale-[0.98] whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        <span class="hidden sm:inline">Buat Perangkat Baru</span>
                        <span class="sm:hidden text-xs">Perangkat Baru</span>
                    </a>
                @endif
            </div>
        </header>

        <!-- Main View Content -->
        <main class="flex-1 overflow-y-auto p-3.5 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>
@endsection
