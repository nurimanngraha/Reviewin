@extends('layouts.admin')

@section('title', 'Daftar Bisnis - ReviewIn')
@section('page_title', 'Manajemen Bisnis Terdaftar')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        
        <form method="GET" action="{{ route('admin.businesses.index') }}" class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       placeholder="Cari nama bisnis, kategori, atau alamat..."
                       class="w-full rounded-xl border-slate-300 text-xs py-2 pl-3 pr-20 focus:border-brand-500 focus:ring-brand-500">
                <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold">
                    Cari
                </button>
            </div>
        </form>

        <a href="{{ route('admin.businesses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-sm transition-all whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
            <span>+ Daftarkan Bisnis Baru</span>
        </a>
    </div>

    <!-- Businesses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($businesses as $business)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-2.5 py-0.5 rounded-full border border-brand-100">
                                {{ $business->category ?? 'Umum' }}
                            </span>
                            <h3 class="text-base font-bold text-slate-900 mt-2 hover:text-brand-600">
                                <a href="{{ route('admin.businesses.show', $business) }}">{{ $business->name }}</a>
                            </h3>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full {{ $business->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}" title="{{ $business->is_active ? 'Aktif' : 'Nonaktif' }}"></span>
                    </div>

                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                        {{ $business->address ?: 'Alamat belum dilengkapi' }}
                    </p>

                    <!-- Owner & Stats Badge -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="text-slate-400">Pemilik:</span>
                            <span class="font-semibold text-slate-800">{{ $business->user?->name ?? 'Belum ada' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="text-slate-400">Perangkat Terhubung:</span>
                            <span class="font-bold text-slate-900">{{ $business->devices_count }} unit</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span class="text-slate-400">Total Review Traffic:</span>
                            <span class="font-bold text-emerald-600">{{ number_format($business->scans_count) }} scans</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ $business->google_review_url }}" target="_blank" title="Test Google Review Link"
                       class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                        <span>Cek Review</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.businesses.show', $business) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>
                        <a href="{{ route('admin.businesses.edit', $business) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
                Belum ada bisnis terdaftar.
            </div>
        @endforelse
    </div>

    @if($businesses->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200">
            {{ $businesses->links() }}
        </div>
    @endif

</div>
@endsection
