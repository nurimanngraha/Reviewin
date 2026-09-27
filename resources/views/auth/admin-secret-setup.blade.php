<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Rahasia Akun Administrator - CreTech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans flex flex-col justify-between py-10 px-4 sm:px-6">

    <div class="max-w-xl mx-auto w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-semibold mb-4">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Portal Rahasia (Tidak Ada Tombol Publik di Website)
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Kelola & Reset Akun Admin</h1>
            <p class="mt-2 text-sm text-slate-400">
                Gunakan halaman rahasia ini untuk mendaftarkan akun Admin baru atau me-reset password akun Admin yang lupa.
            </p>
        </div>

        @if(session('success') || !empty($statusMessage))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-medium">{{ session('success') ?? $statusMessage }}</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 px-3 py-1.5 rounded-lg transition-colors">
                        Buka Halaman Login &rarr;
                    </a>
                </div>
            </div>
        @endif

        @if($errors->any() || !empty($errorMessage))
            <div class="mb-6 p-4 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm">
                <div class="font-bold mb-1">Periksa kembali data formulir:</div>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @if(!empty($errorMessage))
                        <li>{{ $errorMessage }}</li>
                    @endif
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden mb-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 rounded-full blur-2xl -mr-16 -mt-16 pointer-events-none"></div>

            <h2 class="text-lg font-bold text-white mb-1 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Daftar Admin Baru / Ganti Password
            </h2>
            <p class="text-xs text-slate-400 mb-6">
                Masukkan email admin. Jika email sudah ada di sistem, <strong>password-nya akan di-reset</strong> ke password baru. Jika belum ada, akun akan <strong>dibuat sebagai Admin</strong>.
            </p>

            <form action="{{ url('/system/admin-setup?key=' . $secret) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="key" value="{{ $secret }}">

                <div>
                    <label for="name" class="block text-xs font-medium text-slate-300 mb-1.5">Nama Lengkap Admin</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', 'Administrator CreTech') }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all placeholder-slate-500"
                        placeholder="Contoh: Super Admin">
                </div>

                <div>
                    <label for="email" class="block text-xs font-medium text-slate-300 mb-1.5">Alamat Email Admin</label>
                    <input type="email" id="email" name="email" required value="{{ old('email', 'admin@cretech.com') }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all placeholder-slate-500"
                        placeholder="admin@cretech.com">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-medium text-slate-300 mb-1.5">Password Baru</label>
                        <input type="password" id="password" name="password" required minlength="6"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all placeholder-slate-500"
                            placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-slate-300 mb-1.5">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700/80 text-white text-sm focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all placeholder-slate-500"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold text-sm shadow-lg shadow-brand-600/30 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Akun / Reset Password Admin
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Admin Yang Ada di Database -->
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6">
            <h3 class="text-sm font-bold text-slate-300 mb-2 flex items-center justify-between">
                <span>Daftar Akun Admin Terdaftar</span>
                <span class="text-xs font-normal text-slate-500">{{ count($existingAdmins) }} akun ditemukan</span>
            </h3>

            @if(count($existingAdmins) > 0)
                <div class="divide-y divide-slate-800 text-xs">
                    @foreach($existingAdmins as $admin)
                        <div class="py-2.5 flex items-center justify-between gap-2">
                            <div>
                                <div class="font-bold text-slate-200">{{ $admin->name }}</div>
                                <div class="text-slate-400 font-mono">{{ $admin->email }}</div>
                            </div>
                            <button type="button" onclick="autofillAdmin('{{ $admin->name }}', '{{ $admin->email }}')"
                                class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors text-[11px] font-medium border border-slate-700">
                                Pilih & Reset Password
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-500 py-2">Belum ada akun admin di database. Isi form di atas untuk membuat yang pertama.</p>
            @endif

            <div class="mt-4 pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                <a href="{{ route('login') }}" class="hover:text-white transition-colors flex items-center gap-1 text-brand-400 font-medium">
                    &larr; Ke Halaman Login
                </a>
                <span>CreTech Security Protection</span>
            </div>
        </div>
    </div>

    <script>
        function autofillAdmin(name, email) {
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('password').focus();
        }
    </script>
</body>
</html>
