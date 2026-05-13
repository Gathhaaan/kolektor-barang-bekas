@extends('layouts.app')
@section('title', 'Dashboard Penerima')
@section('page-title', 'Dashboard Penerima')
@section('sidebar-nav')
    <a href="{{ route('recipient.dashboard') }}" class="sidebar-link {{ request()->routeIs('recipient.dashboard') ? 'active' : '' }}">📊 <span>Dashboard</span></a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi</p>
    <a href="{{ route('recipient.catalog.index') }}" class="sidebar-link {{ request()->routeIs('recipient.catalog*') ? 'active' : '' }}">🛒 <span>Katalog Donasi</span></a>
    <a href="{{ route('recipient.requests.index') }}" class="sidebar-link {{ request()->routeIs('recipient.requests*') ? 'active' : '' }}">📋 <span>Permintaan Saya</span></a>
@endsection
@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-slate-800">{{ $stats['total_requests'] }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Permintaan</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-amber-500">{{ $stats['pending'] }}</p>
        <p class="text-sm text-slate-500 mt-1">⏳ Menunggu</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-emerald-500">{{ $stats['accepted'] }}</p>
        <p class="text-sm text-slate-500 mt-1">✅ Diterima</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-red-500">{{ $stats['rejected'] }}</p>
        <p class="text-sm text-slate-500 mt-1">❌ Ditolak</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Browse CTA -->
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white">
        <p class="text-indigo-200 text-sm mb-2">Barang tersedia saat ini</p>
        <p class="text-4xl font-black">{{ $recentAvailable->count() }}+</p>
        <p class="text-indigo-300 text-sm mt-1 mb-4">barang menunggu di katalog</p>
        <a href="{{ route('recipient.catalog.index') }}"
           class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-5 py-2.5 rounded-xl hover:bg-indigo-50 transition-colors text-sm">
            🛒 Browse Katalog →
        </a>
    </div>

    <!-- My Recent Requests -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">📋 Permintaan Terbaru</h2>
            <a href="{{ route('recipient.requests.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($myRequests as $req)
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
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
            <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada permintaan</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recently Available -->
@if($recentAvailable->isNotEmpty())
<div class="mt-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-slate-800">✨ Baru Tersedia</h2>
        <a href="{{ route('recipient.catalog.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($recentAvailable as $donation)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
            @if($donation->firstPhoto())
                <img src="{{ $donation->firstPhoto() }}" class="w-full h-36 object-cover">
            @else
                <div class="w-full h-36 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                    <span class="text-4xl">{{ $donation->category->icon }}</span>
                </div>
            @endif
            <div class="p-4">
                <p class="font-bold text-slate-800 truncate text-sm">{{ $donation->title }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $donation->category->name }} · {{ $donation->conditionLabel() }}</p>
                <a href="{{ route('recipient.catalog.show', $donation) }}"
                   class="mt-3 block text-center py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-colors">
                    Ajukan Permintaan →
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
