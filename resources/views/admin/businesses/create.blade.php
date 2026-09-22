@extends('layouts.admin')

@section('title', 'Daftarkan Bisnis Baru - ReviewIn')
@section('page_title', 'Daftarkan Bisnis Baru')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <div class="pb-4 mb-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-900">Informasi Bisnis Baru</h3>
            <p class="text-xs text-slate-500 mt-0.5">Daftarkan profil bisnis dan link tujuan Google Review.</p>
        </div>

        <form action="{{ route('admin.businesses.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="user_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Pemilik Bisnis (Akun User) <span class="text-rose-500">*</span>
                </label>
                <select id="user_id" name="user_id" required class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">-- Pilih Akun Pemilik --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Bisnis / Toko / Cabang <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="Contoh: Kopi Kenangan Senopati"
                       class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}"
                           placeholder="Contoh: Coffee Shop, Resto, Klinik"
                           class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor Telepon / WA</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           placeholder="08123456789"
                           class="w-full rounded-xl border-slate-300 text-sm py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="2" placeholder="Jl. Senopati No. 12, Kebayoran Baru..."
                          class="w-full rounded-xl border-slate-300 text-sm py-2 px-3 focus:border-brand-500 focus:ring-brand-500">{{ old('address') }}</textarea>
            </div>

            <!-- Google Place ID & Google Review Link with Live Generation -->
            <div class="p-4 rounded-2xl bg-brand-50/50 border border-brand-200/80 space-y-3"
                 x-data="{
                     placeId: '{{ old('google_place_id') }}',
                     reviewUrl: '{{ old('google_review_url') }}',
                     generateUrl() {
                         if (this.placeId && this.placeId.trim() !== '') {
                             this.reviewUrl = 'https://search.google.com/local/writereview?placeid=' + encodeURIComponent(this.placeId.trim());
                         }
                     }
                 }">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="google_place_id" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                            <i class="fab fa-google text-rose-500 mr-1"></i> Google Place ID
                        </label>
                        <a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" 
                           target="_blank" 
                           class="text-[11px] text-brand-600 hover:text-brand-800 font-bold inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-brand-300 shadow-2xs">
                            <i class="fas fa-map-marker-alt text-brand-600 text-[10px]"></i>
                            <span>Cari Place ID</span>
                            <i class="fas fa-external-link-alt text-[9px]"></i>
                        </a>
                    </div>
                    <input type="text" id="google_place_id" name="google_place_id" x-model="placeId" @input="generateUrl()"
                           placeholder="Contoh: ChIJN1t_tDeuEmsRUsoyG83frY4"
                           class="w-full rounded-xl border-slate-300 font-mono text-xs py-2 px-3 focus:border-brand-500 focus:ring-brand-500 bg-white">
                    <p class="text-[11px] text-slate-500 mt-1">
                        Ketik Google Place ID toko untuk otomatis membentuk Link Google Review.
                    </p>
                </div>

                <div>
                    <label for="google_review_url" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                        Link Google Review Bisnis <span class="text-rose-500">*</span>
                    </label>
                    <input type="url" id="google_review_url" name="google_review_url" x-model="reviewUrl" required
                           placeholder="https://search.google.com/local/writereview?placeid=..."
                           class="w-full rounded-xl border-slate-300 font-mono text-xs py-2.5 px-3.5 focus:border-brand-500 focus:ring-brand-500 bg-white">
                    @error('google_review_url')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-slate-500 mt-1">Semua perangkat yang terhubung ke bisnis ini akan otomatis mengarah ke link ini.</p>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.businesses.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/30 text-xs transition-all">
                    Daftarkan Bisnis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
