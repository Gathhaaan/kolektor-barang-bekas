@extends('layouts.app')
@section('title', 'Dashboard Pendonasi')
@section('page-title', 'Dashboard Pendonasi')
@section('page-subtitle', 'Kelola donasi dan lihat poin Anda')
@section('sidebar-nav')
    <a href="{{ route('donor.dashboard') }}" class="sidebar-link {{ request()->routeIs('donor.dashboard') ? 'active' : '' }}">📊 <span>Dashboard</span></a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi Saya</p>
    <a href="{{ route('donor.donations.create') }}" class="sidebar-link {{ request()->routeIs('donor.donations.create') ? 'active' : '' }}">➕ <span>Upload Donasi</span></a>
    <a href="{{ route('donor.donations.index') }}" class="sidebar-link {{ request()->routeIs('donor.donations.index') ? 'active' : '' }}">📦 <span>Donasi Saya</span></a>
@endsection
@section('content')

<!-- Points Banner -->
<div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 mb-6 text-white relative overflow-hidden">
    <div class="absolute right-0 top-0 w-48 h-full opacity-10">
        <div class="w-48 h-48 bg-white rounded-full -translate-y-1/2 translate-x-1/4"></div>
    </div>
    <div class="relative flex items-center justify-between">
        <div>
            <p class="text-indigo-200 text-sm font-medium">Total Poin Kontribusi Anda</p>
            <p class="text-5xl font-black mt-1">{{ $stats['points'] }}</p>
            <p class="text-indigo-300 text-sm mt-1">⭐ Terima kasih atas kontribusi Anda!</p>
        </div>
        <div class="text-right">
            <a href="{{ route('donor.donations.create') }}"
               class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-5 py-2.5 rounded-xl hover:bg-indigo-50 transition-colors text-sm">
                ➕ Upload Donasi
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-slate-800">{{ $stats['total'] }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Donasi</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-amber-500">{{ $stats['pending'] }}</p>
        <p class="text-sm text-slate-500 mt-1">⏳ Pending</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-blue-500">{{ $stats['approved'] }}</p>
        <p class="text-sm text-slate-500 mt-1">✅ Aktif</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-emerald-500">{{ $stats['completed'] }}</p>
        <p class="text-sm text-slate-500 mt-1">🎉 Selesai</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Donations -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">📦 Donasi Terbaru</h2>
            <a href="{{ route('donor.donations.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($donations as $donation)
            <a href="{{ route('donor.donations.show', $donation) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
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
                <a href="{{ route('donor.donations.create') }}" class="mt-3 inline-block text-sm text-indigo-600 font-semibold hover:underline">Upload sekarang →</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Point Logs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">⭐ Riwayat Poin</h2>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($pointLogs as $log)
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">⭐</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800">+{{ $log->points }} Poin</p>
                    <p class="text-xs text-slate-500 truncate">{{ $log->description }}</p>
                    <p class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-slate-400 text-sm">Belum ada riwayat poin</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
