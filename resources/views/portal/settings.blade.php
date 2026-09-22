@extends('layouts.portal')

@section('title', 'Pengaturan Bisnis & Link Google Review - CreTech')
@section('page_title', 'Pengaturan Bisnis & Link Google Review')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @if($businesses->count() > 1)
        <!-- Business Switcher Tabs if owner has multiple stores -->
        <div class="flex items-center gap-2 p-1.5 bg-slate-200/80 rounded-2xl overflow-x-auto text-xs font-semibold">
            @foreach($businesses as $b)
                <a href="{{ route('portal.settings', ['business_id' => $b->id]) }}" 
                   class="px-4 py-2 rounded-xl transition-all whitespace-nowrap {{ $selectedBusiness->id === $b->id ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ $b->name }}
                </a>
            @endforeach
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

                <!-- Google Place ID & Review Link -->
                <div class="p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200 space-y-4"
                     x-data="{
                         placeId: '{{ old('google_place_id', $selectedBusiness->google_place_id) }}',
                         reviewUrl: '{{ old('google_review_url', $selectedBusiness->google_review_url) }}',
                         generateUrl() {
                             if (this.placeId && this.placeId.trim() !== '') {
                                 this.reviewUrl = 'https://search.google.com/local/writereview?placeid=' + encodeURIComponent(this.placeId.trim());
                             }
                         }
                     }">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="google_place_id" class="block text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                <i class="fab fa-google text-rose-500 mr-1"></i> Google Place ID
                            </label>
                            <a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" 
                               target="_blank" 
                               class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-emerald-300 shadow-2xs">
                                <i class="fas fa-map-marker-alt text-emerald-600 text-[10px]"></i>
                                <span>Cari Place ID</span>
                                <i class="fas fa-external-link-alt text-[9px]"></i>
                            </a>
                        </div>
                        <input type="text" id="google_place_id" name="google_place_id" x-model="placeId" @input="generateUrl()"
                               value="{{ old('google_place_id', $selectedBusiness->google_place_id) }}"
                               placeholder="Contoh: ChIJN1t_tDeuEmsRUsoyG83frY4"
                               class="w-full rounded-xl border-emerald-300 font-mono text-xs py-2.5 px-3.5 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm bg-white">
                        <p class="text-[11px] text-emerald-800/80 mt-1.5">
                            Mengisi Google Place ID akan secara otomatis membentuk link Google Review langsung ke form bintang 5 pelanggan.
                        </p>

                        <!-- Brief Step-by-Step Guide on How to Get Google Place ID -->
                        <div class="mt-2.5 p-3 rounded-xl bg-white/90 border border-emerald-200 text-xs text-slate-600">
                            <div class="flex items-center gap-1.5 text-slate-800 font-bold text-[11px] uppercase tracking-wider mb-1.5">
                                <i class="fas fa-info-circle text-emerald-600"></i>
                                <span>Cara Singkat Mendapatkan Google Place ID:</span>
                            </div>
                            <ol class="list-decimal list-inside space-y-1 text-[11px] leading-relaxed text-slate-600 pl-0.5">
                                <li>Klik tombol <strong class="text-emerald-700">"Cari Place ID"</strong> di atas atau buka <a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" target="_blank" class="text-emerald-600 underline font-semibold">Google Place ID Finder</a>.</li>
                                <li>Ketik nama toko atau alamat cabang bisnis Anda pada kolom pencarian di peta.</li>
                                <li>Pilih bisnis Anda dari rekomendasi yang tampil.</li>
                                <li>Salin kode <strong>Place ID</strong> (berawalan <code class="font-mono font-bold bg-slate-100 px-1.5 py-0.5 rounded border border-slate-300 text-slate-900">ChIJ...</code>) lalu tempel ke kolom di atas.</li>
                            </ol>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="google_review_url" class="block text-xs font-bold text-emerald-950 uppercase tracking-wider">
                                Link Google Review <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-emerald-700 font-semibold cursor-pointer underline" onclick="window.open('https://support.google.com/business/answer/3474122', '_blank')">
                                Petunjuk Google Profile
                            </span>
                        </div>
                        <input type="url" id="google_review_url" name="google_review_url" x-model="reviewUrl" required
                               class="w-full rounded-xl border-emerald-300 font-mono text-xs py-2.5 px-3.5 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm bg-white">
                        @error('google_review_url')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
    @else
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Belum Ada Toko Terhubung</h3>
            <p class="text-xs text-slate-500 mt-1.5 max-w-md mx-auto leading-relaxed">
                Toko baru otomatis terdaftar ketika Anda melakukan aktivasi pada kartu Card Review QR Code / NFC yang baru.
            </p>
        </div>
    @endif

</div>
@endsection
