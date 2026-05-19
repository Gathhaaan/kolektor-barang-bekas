@extends('layouts.app')

@section('title', 'Permintaan Donasi')
@section('page-title', 'Permintaan Donasi')
@section('page-subtitle', 'Kelola semua permintaan barang dari penerima')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        📊 <span>Dashboard</span>
    </a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Manajemen</p>
    <a href="{{ route('admin.donations.index') }}" class="sidebar-link {{ request()->routeIs('admin.donations*') ? 'active' : '' }}">
        📦 <span>Donasi</span>
    </a>
    <a href="{{ route('admin.requests.index') }}" class="sidebar-link {{ request()->routeIs('admin.requests*') ? 'active' : '' }}">
        🙋 <span>Permintaan</span>
        @php $pendingRequestsCount = \App\Models\DonationRequest::where('status', 'pending')->count(); @endphp
        @if($pendingRequestsCount > 0)
            <span class="ml-auto bg-amber-400 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $pendingRequestsCount }}</span>
        @endif
    </a>
    <a href="{{ route('admin.assignments.index') }}" class="sidebar-link {{ request()->routeIs('admin.assignments*') ? 'active' : '' }}">
        🚚 <span>Penugasan</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        🗂️ <span>Kategori</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        👥 <span>Pengguna</span>
    </a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Laporan</p>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
        📈 <span>Laporan</span>
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
        <div class="flex gap-2">
            <a href="{{ route('admin.requests.index') }}" 
               class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ !request('status') ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                Semua Permintaan
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('status') === 'pending' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                Menunggu
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <th class="px-6 py-4 font-semibold">Barang</th>
                    <th class="px-6 py-4 font-semibold">Penerima</th>
                    <th class="px-6 py-4 font-semibold">Pesan</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($requests as $req)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg flex-shrink-0">
                                {{ $req->donation->category->icon }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('admin.donations.show', $req->donation) }}" class="font-semibold text-slate-800 text-sm hover:text-indigo-600">
                                    {{ $req->donation->title }}
                                </a>
                                <p class="text-xs text-slate-500">{{ $req->donation->conditionLabel() }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <img src="{{ $req->user->avatarUrl() }}" class="w-8 h-8 rounded-full">
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $req->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $req->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600 max-w-xs truncate" title="{{ $req->message }}">{{ $req->message ?: '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @php $color = $req->statusColor(); @endphp
                        <span class="badge bg-{{ $color }}-100 text-{{ $color }}-700">{{ $req->statusLabel() }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $req->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($req->status === 'pending')
                            <a href="{{ route('admin.donations.show', $req->donation) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">Tinjau & Tugaskan Kurir</a>
                        @else
                            <a href="{{ route('admin.donations.show', $req->donation) }}" class="text-slate-400 hover:text-slate-600 text-sm">Lihat Detail</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                        <span class="text-4xl mb-3 block">📭</span>
                        <p>Tidak ada permintaan donasi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($requests->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection
