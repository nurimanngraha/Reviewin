@extends('layouts.app')

@section('title', 'Aktivasi Perangkat Google Review - ' . $device->device_code)

@section('body')
<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto w-full">
        
        <!-- Header Brand -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block mb-3 group hover:scale-105 transition-transform">
                <img src="{{ asset('assets/CreTechlogin.svg') }}" alt="CreTech" class="h-14 sm:h-16 w-auto mx-auto object-contain drop-shadow-sm">
            </a>
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-100 border border-amber-200 text-amber-900 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>Aktivasi Perangkat Baru (Scan Pertama)</span>
                </div>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Setup Kartu Google Review</h1>
            <p class="mt-1.5 text-xs sm:text-sm text-slate-600">
                Hubungkan perangkat fisik QR & NFC ini ke bisnis Anda menggunakan Kode Kartu dan Google Place ID.
            </p>
        </div>

        <!-- Device Card Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex flex-col items-center justify-center font-mono shadow">
                        <i class="fas fa-qrcode text-base text-amber-400"></i>
                        <span class="text-[9px] uppercase font-bold tracking-wider">NFC/QR</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-medium text-slate-400 uppercase tracking-wider block">ID Perangkat</span>
                        <span class="text-base sm:text-lg font-black text-slate-900 font-mono tracking-tight">{{ $device->device_code }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        <i class="fas fa-clock mr-1 text-[10px]"></i> Unactivated
                    </span>
                    <span class="block text-[11px] text-slate-400 mt-1 capitalize">{{ str_replace('_', ' ', $device->type) }}</span>
                </div>
            </div>

            <!-- Activation Code Badge from System -->
            @if(!empty($device->activation_code))
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between bg-slate-50 -mx-5 -mb-5 p-4 rounded-b-2xl">
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 block uppercase tracking-wider">Kode Kartu (Activation Code) Resmi</span>
                        <span class="text-sm sm:text-base font-mono font-black text-brand-700 tracking-wider select-all">{{ $device->activation_code }}</span>
                    </div>
                    <span class="text-xs text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg font-medium shadow-xs">
                        <i class="fas fa-key text-brand-600 mr-1"></i> Telah Disiapkan Admin
                    </span>
                </div>
            @endif
        </div>

        @guest
            <!-- Authentication Required Gate -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 sm:p-8 text-center">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 text-brand-600 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-lock text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Login Pemilik Bisnis Diperlukan</h2>
                <p class="mt-2 text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
                    Untuk melanjutkan aktivasi dan mengaitkan perangkat fisik ini ke profil bisnis Anda, silakan masuk ke akun Anda terlebih dahulu.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('login', ['redirect' => '/activate/' . $device->device_code]) }}" 
                       class="inline-flex justify-center items-center px-6 py-3 rounded-xl font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/20 text-sm transition-all gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk ke Akun Pemilik Bisnis</span>
                    </a>
                    <a href="{{ route('register', ['redirect' => '/activate/' . $device->device_code]) }}" 
                       class="inline-flex justify-center items-center px-6 py-3 rounded-xl font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-sm transition-colors">
                        Daftar Akun Baru
                    </a>
                </div>
            </div>
        @else
            <!-- Authenticated Activation Form -->
            <div x-data="{ 
                    useExisting: {{ $userBusinesses->count() > 0 ? 'true' : 'false' }},
                    selectedBusinessId: '{{ $userBusinesses->first()?->id ?? '' }}',
                    activationCode: '{{ old('activation_code', $device->activation_code ?? '') }}',
                    placeId: '{{ old('google_place_id', $userBusinesses->first()?->google_place_id ?? '') }}',
                    businessName: '{{ old('business_name', '') }}',
                    businesses: {{ Js::from($userBusinesses) }},
                    updateBusiness() {
                        const b = this.businesses.find(item => item.id == this.selectedBusinessId);
                        if (b) {
                            this.placeId = b.google_place_id || '';
                        }
                    },
                    get previewUrl() {
                        if (!this.placeId || this.placeId.trim() === '') return '';
                        return 'https://search.google.com/local/writereview?placeid=' + encodeURIComponent(this.placeId.trim());
                    }
                 }" 
                 class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 sm:p-8">
                
                <div class="flex items-center gap-3 pb-5 mb-5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Masuk sebagai:</p>
                        <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }} <span class="text-slate-400 font-normal">({{ auth()->user()->email }})</span></p>
                    </div>
                </div>

                <form action="{{ route('device.activate.process', $device->device_code) }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- 1. Activation Code (Kode Kartu) -->
                    <div class="bg-amber-50/60 border border-amber-200/80 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="activation_code" class="block text-xs font-bold text-amber-950 uppercase tracking-wider">
                                <i class="fas fa-shield-alt text-amber-600 mr-1"></i> Kode Kartu (Activation Code) <span class="text-rose-500">*</span>
                            </label>
                            @if(!empty($device->activation_code))
                                <button type="button" 
                                        @click="activationCode = '{{ $device->activation_code }}'"
                                        class="text-[11px] text-brand-600 hover:text-brand-800 font-semibold underline">
                                    Pakai Kode: {{ $device->activation_code }}
                                </button>
                            @endif
                        </div>
                        <input type="text" id="activation_code" name="activation_code" x-model="activationCode" required
                               placeholder="Contoh: ACT-123456"
                               class="w-full rounded-xl bg-white shadow-xs focus:ring-brand-500 text-sm py-2.5 px-3.5 font-mono font-bold tracking-wider uppercase text-slate-900 {{ $errors->has('activation_code') ? 'border-rose-500 focus:border-rose-500 ring-rose-500' : 'border-amber-300 focus:border-brand-500' }}">
                        @error('activation_code')
                            <p class="text-xs text-rose-600 font-medium mt-1.5 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            Masukkan atau konfirmasi Kode Kartu unik yang tertera pada kartu/perangkat fisik atau yang disediakan sistem di atas.
                        </p>
                    </div>

                    <!-- 2. Nama Bisnis -->
                    @if($userBusinesses->count() > 0)
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Pilih Bisnis untuk Perangkat Ini</label>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <button type="button" 
                                        @click="useExisting = true; updateBusiness();"
                                        :class="useExisting ? 'border-brand-600 bg-brand-50/50 text-brand-700 font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50 font-medium'"
                                        class="p-3 text-xs rounded-xl border text-center transition-all">
                                    Bisnis Saya ({{ $userBusinesses->count() }})
                                </button>
                                <button type="button" 
                                        @click="useExisting = false; placeId = ''; businessName = '';"
                                        :class="!useExisting ? 'border-brand-600 bg-brand-50/50 text-brand-700 font-bold' : 'border-slate-200 text-slate-600 hover:bg-slate-50 font-medium'"
                                        class="p-3 text-xs rounded-xl border text-center transition-all">
                                    + Daftarkan Bisnis Baru
                                </button>
                            </div>

                            <div x-show="useExisting" class="space-y-4">
                                <div>
                                    <label for="business_id" class="block text-xs font-semibold text-slate-700 mb-1">Bisnis Terdaftar</label>
                                    <select id="business_id" name="business_id" x-model="selectedBusinessId" @change="updateBusiness()"
                                            class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5">
                                        @foreach($userBusinesses as $biz)
                                            <option value="{{ $biz->id }}">{{ $biz->name }} ({{ $biz->category ?? 'Bisnis' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- New Business Fields -->
                    <div x-show="!useExisting" class="space-y-4 pt-1">
                        <div>
                            <label for="business_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Bisnis <span class="text-rose-500">*</span></label>
                            <input type="text" id="business_name" name="business_name" x-model="businessName"
                                   placeholder="Contoh: Kopi Kenangan Senopati"
                                   class="w-full rounded-xl shadow-sm focus:ring-brand-500 text-sm py-2.5 px-3.5 {{ $errors->has('business_name') ? 'border-rose-500 focus:border-rose-500' : 'border-slate-300 focus:border-brand-500' }}">
                            @error('business_name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="category" class="block text-xs font-semibold text-slate-700 mb-1">Kategori (Opsional)</label>
                                <input type="text" id="category" name="category" value="{{ old('category') }}"
                                       placeholder="Contoh: Coffee Shop & Bakery"
                                       class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5">
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">No. Kontak (Opsional)</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                       placeholder="08123456789"
                                       class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Google Place ID -->
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-1">
                            <label for="google_place_id" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                <i class="fab fa-google text-rose-500 mr-1"></i> Google Place ID <span class="text-rose-500">*</span>
                            </label>
                            <a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" 
                               target="_blank" 
                               class="text-[11px] text-brand-600 hover:text-brand-800 font-bold inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-brand-50 border border-brand-200 transition-colors shadow-2xs">
                                <i class="fas fa-map-marker-alt text-brand-600 text-[10px]"></i>
                                <span>Cari Place ID</span>
                                <i class="fas fa-external-link-alt text-[9px]"></i>
                            </a>
                        </div>
                        <input type="text" id="google_place_id" name="google_place_id" x-model="placeId" required
                               placeholder="Contoh: ChIJN1t_tDeuEmsRUsoyG83frY4"
                               class="w-full rounded-xl shadow-sm focus:ring-brand-500 py-2.5 px-3.5 font-mono text-xs text-slate-900 {{ $errors->has('google_place_id') ? 'border-rose-500 focus:border-rose-500' : 'border-slate-300 focus:border-brand-500' }}">
                        @error('google_place_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Brief Step-by-Step Guide on How to Get Google Place ID -->
                        <div class="mt-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600">
                            <div class="flex items-center gap-1.5 text-slate-800 font-bold text-[11px] uppercase tracking-wider mb-1.5">
                                <i class="fas fa-info-circle text-brand-600"></i>
                                <span>Cara Singkat Mendapatkan Google Place ID:</span>
                            </div>
                            <ol class="list-decimal list-inside space-y-1 text-[11px] leading-relaxed text-slate-600 pl-0.5">
                                <li>Klik tombol <strong class="text-brand-700">"Cari Place ID"</strong> di atas atau buka <a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" target="_blank" class="text-brand-600 underline font-semibold">Google Place ID Finder</a>.</li>
                                <li>Ketik nama toko atau alamat cabang bisnis Anda pada kolom pencarian di peta.</li>
                                <li>Pilih bisnis Anda dari rekomendasi yang tampil.</li>
                                <li>Salin kode <strong>Place ID</strong> (berawalan <code class="font-mono font-bold bg-white px-1.5 py-0.5 rounded border border-slate-300 text-slate-900">ChIJ...</code>) lalu tempel ke kolom di atas.</li>
                            </ol>
                        </div>

                        <!-- Live URL Preview -->
                        <div x-show="previewUrl" class="mt-2.5 p-3 rounded-xl bg-emerald-50/70 border border-emerald-200">
                            <span class="text-[10px] font-bold text-emerald-900 block uppercase tracking-wider mb-1">Preview Link Google Review Otomatis:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-[11px] text-emerald-700 truncate block flex-1" x-text="previewUrl"></span>
                                <a :href="previewUrl" target="_blank" class="text-xs text-brand-600 hover:text-brand-800 font-bold whitespace-nowrap">
                                    Test Link <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Label Penempatan Perangkat (Opsional) -->
                    <div class="pt-1">
                        <label for="device_label" class="block text-xs font-semibold text-slate-700 mb-1">Label Penempatan Kartu (Opsional)</label>
                        <input type="text" id="device_label" name="device_label" value="{{ old('device_label', $device->name) }}"
                               placeholder="Contoh: Meja Kasir Utama, Meja 12, Pintu Keluar"
                               class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2 px-3.5">
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full py-3.5 px-4 rounded-xl font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/30 transition-all text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle text-base"></i>
                            <span>Aktivasi & Hubungkan Perangkat</span>
                        </button>
                    </div>
                </form>
            </div>
        @endguest

        <div class="mt-8 text-center text-xs text-slate-400">
            Sistem Pengelolaan QR Code & NFC Google Review &copy; CreTech
        </div>
    </div>
</div>
@endsection
