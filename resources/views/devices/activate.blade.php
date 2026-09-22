@extends('layouts.app')

@section('title', 'Aktivasi Perangkat Google Review - ' . $device->device_code)

@section('body')
<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto w-full">
        
        <!-- Header Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-amber-100 border border-amber-200 text-amber-900 text-xs font-semibold mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Aktivasi Perangkat Baru (Scan Pertama)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Setup Kartu / QR Code Google Review</h1>
            <p class="mt-2 text-sm text-slate-600">
                Hubungkan perangkat fisik ini ke bisnis Anda agar pelanggan dapat langsung memberikan ulasan bintang 5 di Google.
            </p>
        </div>

        <!-- Device Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-mono font-bold text-sm shadow">
                    QR/NFC
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-500 block uppercase tracking-wider">Kode Unik Perangkat</span>
                    <span class="text-lg font-extrabold text-slate-900 font-mono tracking-tight">{{ $device->device_code }}</span>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                Belum Aktif
            </span>
        </div>

        @guest
            <!-- Authentication Required Gate -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 sm:p-8 text-center">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 text-brand-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Autentikasi Pemilik Bisnis</h2>
                <p class="mt-2 text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
                    Untuk memastikan keamanan dan menghubungkan perangkat ini secara permanen ke akun bisnis Anda, silakan login terlebih dahulu.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('login', ['redirect' => '/activate/' . $device->device_code]) }}" 
                       class="inline-flex justify-center items-center px-6 py-3 rounded-xl font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/20 text-sm transition-all">
                        Masuk ke Akun Saya
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
                    reviewUrl: '{{ $userBusinesses->first()?->google_review_url ?? '' }}',
                    businesses: {{ Js::from($userBusinesses) }},
                    updateReviewUrl() {
                        const b = this.businesses.find(item => item.id == this.selectedBusinessId);
                        if (b && b.google_review_url) {
                            this.reviewUrl = b.google_review_url;
                        }
                    }
                 }" 
                 class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 sm:p-8">
                
                <div class="flex items-center gap-3 pb-5 mb-5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Login sebagai Pemilik Bisnis:</p>
                        <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
                    </div>
                </div>

                <form action="{{ route('device.activate.process', $device->device_code) }}" method="POST" class="space-y-5">
                    @csrf

                    @if($userBusinesses->count() > 0)
                        <!-- Toggle existing vs new business -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Pilih Bisnis</label>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <button type="button" 
                                        @click="useExisting = true"
                                        :class="useExisting ? 'border-brand-600 bg-brand-50/50 text-brand-700 font-semibold' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                                        class="p-3 text-xs rounded-xl border text-center transition-all">
                                    Bisnis Terdaftar ({{ $userBusinesses->count() }})
                                </button>
                                <button type="button" 
                                        @click="useExisting = false; reviewUrl = '';"
                                        :class="!useExisting ? 'border-brand-600 bg-brand-50/50 text-brand-700 font-semibold' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                                        class="p-3 text-xs rounded-xl border text-center transition-all">
                                    + Tambah Bisnis Baru
                                </button>
                            </div>

                            <div x-show="useExisting" class="space-y-4">
                                <div>
                                    <label for="business_id" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Bisnis Anda</label>
                                    <select id="business_id" name="business_id" x-model="selectedBusinessId" @change="updateReviewUrl()"
                                            class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                                        @foreach($userBusinesses as $biz)
                                            <option value="{{ $biz->id }}">{{ $biz->name }} ({{ $biz->category ?? 'Bisnis' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- New Business Fields -->
                    <div x-show="!useExisting" class="space-y-4 pt-2">
                        <div>
                            <label for="business_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Bisnis / Toko / Resto <span class="text-rose-500">*</span></label>
                            <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}"
                                   placeholder="Contoh: Kopi Kenangan Senopati"
                                   class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5">
                            @error('business_name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="category" class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                                <input type="text" id="category" name="category" value="{{ old('category') }}"
                                       placeholder="Contoh: Cafe & Resto"
                                       class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5">
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">No. Kontak / WhatsApp</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                       placeholder="08123456789"
                                       class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Bisnis</label>
                            <textarea id="address" name="address" rows="2"
                                      placeholder="Alamat lengkap lokasi bisnis..."
                                      class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2 px-3">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- Label Device -->
                    <div class="pt-2">
                        <label for="device_label" class="block text-xs font-semibold text-slate-700 mb-1">Label Lokasi Perangkat (Opsional)</label>
                        <input type="text" id="device_label" name="device_label" value="{{ old('device_label', $device->name) }}"
                               placeholder="Contoh: Meja Kasir Utama, Meja 12, Pintu Keluar"
                               class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5">
                        <p class="text-[11px] text-slate-500 mt-1">Membantu Anda membedakan lokasi penempatan kartu/stand QR ini.</p>
                    </div>

                    <!-- Google Review URL -->
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-1">
                            <label for="google_review_url" class="block text-xs font-semibold text-slate-700">Link Google Review <span class="text-rose-500">*</span></label>
                            <span class="text-[11px] text-brand-600 font-medium cursor-pointer" onclick="window.open('https://support.google.com/business/answer/3474122', '_blank')">Cara dapat link Google Review?</span>
                        </div>
                        <input type="url" id="google_review_url" name="google_review_url" x-model="reviewUrl" required
                               placeholder="https://g.page/r/xxxxxx/review atau https://maps.app.goo.gl/xxxxxx"
                               class="w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm py-2.5 px-3.5 font-mono text-xs @error('google_review_url') border-rose-500 @enderror">
                        @error('google_review_url')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-500 mt-1">Setelah perangkat aktif, setiap scan QR atau tap NFC pelanggan akan langsung diarahkan ke link ini.</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full py-3.5 px-4 rounded-xl font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/30 transition-all text-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Aktivasi Perangkat Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        @endguest

        <div class="mt-8 text-center text-xs text-slate-400">
            Sistem Pengelolaan QR Code & NFC Google Review &copy; ReviewIn
        </div>
    </div>
</div>
@endsection
