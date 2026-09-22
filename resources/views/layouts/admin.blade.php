@extends('layouts.app')

@section('body')
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-100 flex">

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
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-2xl">
        
        <!-- Brand Logo -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-400 flex items-center justify-center text-white shadow-lg shadow-brand-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-bold text-white tracking-tight">ReviewIn</span>
                    <span class="block text-[10px] uppercase tracking-wider text-brand-400 font-semibold">Admin Panel</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
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
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Topbar Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h1 class="text-lg font-bold text-slate-900">@yield('page_title', 'Admin Panel')</h1>
            </div>

            <!-- Topbar Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.devices.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold rounded-xl bg-brand-600 text-white hover:bg-brand-700 shadow-sm transition-all hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Buat Perangkat Baru</span>
                </a>
            </div>
        </header>

        <!-- Main View Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>
@endsection
