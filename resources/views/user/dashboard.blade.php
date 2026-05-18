@extends('layouts.app')
@section('title', 'Dashboard User')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Kelola donasi dan permintaan Anda')
@section('sidebar-nav')
    <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">📊 <span>Dashboard</span></a>
    
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Katalog</p>
    <a href="{{ route('user.catalog.index') }}" class="sidebar-link {{ request()->routeIs('user.catalog*') ? 'active' : '' }}">🛒 <span>Katalog Barang</span></a>
    
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Aktivitas Saya</p>
    <a href="{{ route('user.donations.create') }}" class="sidebar-link {{ request()->routeIs('user.donations.create') ? 'active' : '' }}">➕ <span>Upload Donasi</span></a>
    <a href="{{ route('user.donations.index') }}" class="sidebar-link {{ request()->routeIs('user.donations.index') && !request()->routeIs('user.donations.create') ? 'active' : '' }}">📦 <span>Donasi Saya</span></a>
    <a href="{{ route('user.requests.index') }}" class="sidebar-link {{ request()->routeIs('user.requests*') ? 'active' : '' }}">📋 <span>Permintaan Saya</span></a>
@endsection
@section('content')

<!-- Points Banner -->
<div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 mb-6 text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="absolute right-0 top-0 w-48 h-full opacity-10">
        <div class="w-48 h-48 bg-white rounded-full -translate-y-1/2 translate-x-1/4"></div>
    </div>
    <div class="relative z-10 flex items-center gap-4">
        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-3xl">⭐</div>
        <div>
            <p class="text-indigo-200 text-sm font-medium">Total Poin Kontribusi Anda</p>
            <p class="text-4xl font-black mt-1">{{ $stats['points'] }} <span class="text-lg font-normal text-indigo-200">Poin</span></p>
        </div>
    </div>
    <div class="relative z-10 flex gap-3 w-full md:w-auto">
        <a href="{{ route('user.donations.create') }}"
           class="flex-1 md:flex-none text-center bg-white text-indigo-700 font-bold px-5 py-2.5 rounded-xl hover:bg-indigo-50 transition-colors text-sm">
            ➕ Upload Donasi
        </a>
        <a href="{{ route('user.catalog.index') }}"
           class="flex-1 md:flex-none text-center bg-indigo-500 text-white font-bold px-5 py-2.5 rounded-xl hover:bg-indigo-400 transition-colors text-sm border border-indigo-400">
            🛒 Cari Barang
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-sm text-slate-500 mb-1">Total Donasi</p>
        <p class="text-2xl font-black text-slate-800">{{ $stats['donations_total'] }}</p>
        <p class="text-xs text-indigo-600 mt-1">{{ $stats['donations_approved'] }} disetujui</p>
    </div>
    <div class="stat-card">
        <p class="text-sm text-slate-500 mb-1">Total Permintaan</p>
        <p class="text-2xl font-black text-slate-800">{{ $stats['requests_total'] }}</p>
        <p class="text-xs text-emerald-600 mt-1">{{ $stats['requests_accepted'] }} diterima</p>
    </div>
    <div class="stat-card">
        <p class="text-sm text-slate-500 mb-1">Donasi Pending</p>
        <p class="text-2xl font-black text-amber-500">{{ $stats['donations_pending'] }}</p>
        <p class="text-xs text-amber-600 mt-1">menunggu verifikasi</p>
    </div>
    <div class="stat-card">
        <p class="text-sm text-slate-500 mb-1">Permintaan Pending</p>
        <p class="text-2xl font-black text-amber-500">{{ $stats['requests_pending'] }}</p>
        <p class="text-xs text-amber-600 mt-1">menunggu persetujuan</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    
    <!-- Recent Donations -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">📦 Donasi Terbaru Saya</h2>
            <a href="{{ route('user.donations.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-50 flex-1">
            @forelse($myDonations as $donation)
            <a href="{{ route('user.donations.show', $donation) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
                @if($donation->firstPhoto())
                    <img src="{{ $donation->firstPhoto() }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                @else
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-lg flex-shrink-0">{{ $donation->category->icon }}</div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $donation->title }}</p>
                    <p class="text-xs text-slate-500">{{ $donation->created_at->diffForHumans() }}</p>
                </div>
                @php $c=$donation->statusColor(); @endphp
                <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700 flex-shrink-0">{{ $donation->statusLabel() }}</span>
            </a>
            @empty
            <div class="px-6 py-12 text-center">
                <p class="text-4xl mb-3">📦</p>
                <p class="text-slate-500 text-sm">Belum ada donasi</p>
                <a href="{{ route('user.donations.create') }}" class="mt-3 inline-block text-sm text-indigo-600 font-semibold hover:underline">Upload sekarang →</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- My Recent Requests -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">📋 Permintaan Terbaru Saya</h2>
            <a href="{{ route('user.requests.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-50 flex-1">
            @forelse($myRequests as $req)
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
                    {{ $req->donation->category->icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-slate-800 truncate">{{ $req->donation->title }}</p>
                    <p class="text-xs text-slate-500">{{ $req->created_at->diffForHumans() }}</p>
                </div>
                @php $rc=$req->statusColor(); @endphp
                <span class="badge bg-{{ $rc }}-100 text-{{ $rc }}-700">{{ $req->statusLabel() }}</span>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-slate-400 text-sm">
                <p class="text-4xl mb-3">🛒</p>
                <p>Belum ada permintaan barang</p>
                <a href="{{ route('user.catalog.index') }}" class="mt-3 inline-block text-sm text-indigo-600 font-semibold hover:underline">Cari di katalog →</a>
            </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Top Donors -->
@if($topDonors->isNotEmpty())
<div class="mt-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-slate-800 text-lg">🏆 Top Pendonasi</h2>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">Peringkat</th>
                        <th class="px-6 py-3 text-left">Pendonasi</th>
                        <th class="px-6 py-3 text-left">Total Donasi Selesai</th>
                        <th class="px-6 py-3 text-left">Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($topDonors as $i => $donor)
                    <tr class="hover:bg-slate-50 {{ $donor->id === auth()->id() ? 'bg-indigo-50/50' : '' }}">
                        <td class="px-6 py-3 text-lg">
                            @if($i===0) 🥇 @elseif($i===1) 🥈 @elseif($i===2) 🥉 @else <span class="text-sm font-bold text-slate-500 px-2">{{ $i+1 }}</span> @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $donor->avatarUrl() }}" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-medium text-slate-800 text-sm">
                                        {{ $donor->name }}
                                        @if($donor->id === auth()->id())
                                            <span class="ml-2 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-bold">Anda</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm font-semibold text-emerald-600">{{ $donor->completed_count }}</td>
                        <td class="px-6 py-3 text-sm font-bold text-amber-600">⭐ {{ $donor->points }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Recently Available -->
@if($recentAvailable->isNotEmpty())
<div class="mt-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-slate-800 text-lg">✨ Baru Tersedia di Katalog</h2>
        <a href="{{ route('user.catalog.index') }}" class="text-sm font-semibold text-indigo-600 hover:underline">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($recentAvailable as $donation)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:border-indigo-100 transition-all group flex flex-col">
            @if($donation->firstPhoto())
                <div class="w-full h-40 overflow-hidden relative">
                    <img src="{{ $donation->firstPhoto() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
            @else
                <div class="w-full h-40 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center group-hover:from-indigo-100 transition-colors">
                    <span class="text-5xl group-hover:scale-110 transition-transform">{{ $donation->category->icon }}</span>
                </div>
            @endif
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <p class="font-bold text-slate-800 line-clamp-2 text-sm">{{ $donation->title }}</p>
                </div>
                <div class="flex items-center gap-2 mt-auto pt-3 text-xs text-slate-500">
                    <span class="flex items-center gap-1">🏷️ {{ $donation->category->name }}</span>
                    <span>·</span>
                    <span class="flex items-center gap-1">{{ $donation->conditionLabel() }}</span>
                </div>
                <a href="{{ route('user.catalog.show', $donation) }}"
                   class="mt-3 block text-center py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-colors">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
