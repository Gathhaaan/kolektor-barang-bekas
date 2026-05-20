@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Manajemen</p>
    <a href="{{ route('admin.donations.index') }}" class="sidebar-link">📦 Donasi</a>
    <a href="{{ route('admin.requests.index') }}" class="sidebar-link">🙋 Permintaan</a>
    <a href="{{ route('admin.assignments.index') }}" class="sidebar-link">🚚 Penugasan</a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link">🗂️ Kategori</a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link active">👥 Pengguna</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Laporan</p>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link">📈 Laporan</a>
@endsection
@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..."
               class="flex-1 min-w-48 px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
        <select name="role" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            <option value="">Semua Role</option>
            @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ request('role')===$role->name?'selected':'' }}>{{ $role->label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">Filter</button>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Pengguna</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Poin</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Bergabung</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatarUrl() }}" class="w-9 h-9 rounded-full">
                            <div>
                                <p class="font-medium text-slate-800 text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3"><span class="badge bg-indigo-100 text-indigo-700">{{ $user->roleName() }}</span></td>
                    <td class="px-6 py-3 text-sm font-semibold text-amber-600">⭐ {{ $user->points }}</td>
                    <td class="px-6 py-3">
                        <span class="badge {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-xs text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        @if($user->id !== auth()->id())
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.users.changeRole', $user) }}">
                                @csrf
                                <select name="role_id" onchange="this.form.submit()" class="text-xs px-2 py-1.5 rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-500 bg-white">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <form method="POST" action="{{ route('admin.users.toggleActive', $user) }}">
                                @csrf
                                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg font-medium {{ $user->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} transition-colors">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-xs text-slate-400 italic">Akun Anda</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada pengguna</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $users->links() }}</div>
</div>
@endsection
