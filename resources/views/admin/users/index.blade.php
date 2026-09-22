@extends('layouts.admin')

@section('title', 'Kelola Pengguna - ReviewIn')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.index') }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($roleFilter) ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">
                Semua Role
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $roleFilter === 'admin' ? 'bg-brand-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">
                Administrator
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'business_owner']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $roleFilter === 'business_owner' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-700 border border-slate-200' }}">
                Pemilik Bisnis
            </a>
        </div>

        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-brand-600 hover:bg-brand-700 text-white shadow-sm transition-all whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span>+ Tambah Pengguna</span>
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4">Nama Pengguna</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Telepon</th>
                        <th class="py-3.5 px-4">Jumlah Bisnis</th>
                        <th class="py-3.5 px-4">Terdaftar</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">{{ $user->email }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                      {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-800 border border-indigo-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'Pemilik Bisnis' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">{{ $user->phone ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $user->businesses_count }} bisnis</td>
                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 rounded-lg hover:bg-rose-50">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada pengguna ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
