@extends('layouts.portal')

@section('title', 'Pengaturan Bisnis & Link Google Review - ReviewIn')
@section('page_title', 'Pengaturan Bisnis & Link Google Review')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ newBranchModal: false }">

    @if($businesses->count() > 1)
        <!-- Business Switcher Tabs if owner has multiple stores -->
        <div class="flex items-center gap-2 p-1.5 bg-slate-200/80 rounded-2xl overflow-x-auto text-xs font-semibold">
            @foreach($businesses as $b)
                <a href="{{ route('portal.settings', ['business_id' => $b->id]) }}" 
                   class="px-4 py-2 rounded-xl transition-all whitespace-nowrap {{ $selectedBusiness->id === $b->id ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ $b->name }}
                </a>
            @endforeach
            <button type="button" @click="newBranchModal = true" class="px-3 py-2 text-emerald-700 hover:text-emerald-800 font-bold whitespace-nowrap">
                + Tambah Cabang Baru
            </button>
        </div>
    @endif

    @if($selectedBusiness)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Profil Bisnis & Link Review</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Kelola informasi toko dan pastikan URL Google Review sudah tepat.</p>
                </div>
                
                @if($selectedBusiness->google_review_url)
                    <a href="{{ $selectedBusiness->google_review_url }}" target="_blank"
                       class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm flex items-center gap-1.5 self-start sm:self-auto">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        <span>Uji Link Google Review</span>
                    </a>
                @endif
            </div>

            <form action="{{ route('portal.settings.business.update', $selectedBusiness) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Google Review URL (Main Focus) -->
                <div class="p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="google_review_url" class="block text-xs font-bold text-emerald-950 uppercase tracking-wider">
                            Link Google Review (Bintang 5) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-emerald-700 font-semibold cursor-pointer underline" onclick="window.open('https://support.google.com/business/answer/3474122', '_blank')">
                            Petunjuk Google Profile
                        </span>
                    </div>
                    <input type="url" id="google_review_url" name="google_review_url" value="{{ old('google_review_url', $selectedBusiness->google_review_url) }}" required
                           class="w-full rounded-xl border-emerald-300 font-mono text-xs py-2.5 px-3.5 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    @error('google_review_url')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-emerald-800/80 mt-2 leading-relaxed">
                        Tip: Dapatkan link ini dari Google Bisnisku (Google Business Profile) &gt; tombol <strong>"Minta ulasan"</strong> atau <strong>"Ask for reviews"</strong>. Format biasanya <code>https://g.page/r/.../review</code> atau link pendek Google Maps.
                    </p>
                </div>

                <!-- Business Name & Category -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Bisnis / Toko <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $selectedBusiness->name) }}" required
                               class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kategori Usaha</label>
                        <input type="text" id="category" name="category" value="{{ old('category', $selectedBusiness->category) }}"
                               placeholder="Contoh: Coffee Shop, Restoran Padang, Barbershop"
                               class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <!-- Phone & Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $selectedBusiness->phone) }}"
                               class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Toko</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $selectedBusiness->email) }}"
                               class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Bisnis</label>
                    <textarea id="address" name="address" rows="2" class="w-full rounded-xl border-slate-300 text-sm py-2 px-3 focus:border-emerald-500 focus:ring-emerald-500">{{ old('address', $selectedBusiness->address) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex items-center justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/30 text-xs transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Add Branch Modal -->
    <div x-show="newBranchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="newBranchModal = false" class="bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl border border-slate-100 text-left">
            <h3 class="text-base font-bold text-slate-900 mb-1">Tambah Cabang / Bisnis Baru</h3>
            <p class="text-xs text-slate-500 mb-4">Daftarkan cabang toko baru untuk dihubungkan dengan perangkat fisik lainnya.</p>

            <form action="{{ route('portal.settings.business.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Bisnis <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Kopi Kenangan Cabang Kemang" class="w-full rounded-xl border-slate-300 text-sm py-2 px-3">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Link Google Review <span class="text-rose-500">*</span></label>
                    <input type="url" name="google_review_url" required placeholder="https://g.page/r/..." class="w-full rounded-xl border-slate-300 text-xs font-mono py-2 px-3">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                        <input type="text" name="category" placeholder="Cafe & Resto" class="w-full rounded-xl border-slate-300 text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">No. Kontak</label>
                        <input type="text" name="phone" placeholder="0812..." class="w-full rounded-xl border-slate-300 text-sm py-2 px-3">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="newBranchModal = false" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow">Simpan Cabang Baru</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
