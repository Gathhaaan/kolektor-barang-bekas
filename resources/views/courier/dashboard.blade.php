@extends('layouts.app')
@section('title', 'Dashboard Kurir')
@section('page-title', 'Dashboard Kurir')
@section('page-subtitle', 'Tugas pengiriman Anda hari ini')
@section('sidebar-nav')
    <a href="{{ route('courier.dashboard') }}" class="sidebar-link {{ request()->routeIs('courier.dashboard') ? 'active' : '' }}">📊 <span>Dashboard</span></a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Tugas</p>
    <a href="{{ route('courier.assignments.index') }}" class="sidebar-link {{ request()->routeIs('courier.assignments*') ? 'active' : '' }}">🚚 <span>Penugasan Saya</span></a>
@endsection
@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-slate-800">{{ $stats['total'] }}</p>
        <p class="text-sm text-slate-500 mt-1">Total Tugas</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-blue-500">{{ $stats['assigned'] }}</p>
        <p class="text-sm text-slate-500 mt-1">🚚 Ditugaskan</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-amber-500">{{ $stats['picked_up'] }}</p>
        <p class="text-sm text-slate-500 mt-1">📦 Sudah Diambil</p>
    </div>
    <div class="stat-card text-center">
        <p class="text-3xl font-black text-emerald-500">{{ $stats['delivered'] }}</p>
        <p class="text-sm text-slate-500 mt-1">✅ Selesai Dikirim</p>
    </div>
</div>

<!-- Today's Tasks -->
@if($todayAssignments->isNotEmpty())
<div class="mb-6">
    <h2 class="font-bold text-slate-800 mb-4">📅 Tugas Hari Ini ({{ today()->format('d M Y') }})</h2>
    <div class="space-y-4">
        @foreach($todayAssignments as $asgn)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                    {{ $asgn->donation->category->icon }}
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $asgn->donation->title }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pendonasi: {{ $asgn->donation->donor->name }}</p>
                        </div>
                        @php $c=$asgn->statusColor(); @endphp
                        <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700">{{ $asgn->statusLabel() }}</span>
                    </div>
                    <p class="text-sm text-slate-600 mt-2">📍 {{ $asgn->donation->pickup_address }}</p>
                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('courier.assignments.show', $asgn) }}"
                           class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-indigo-700 transition-colors">
                            Lihat Detail →
                        </a>
                        @if($asgn->status === 'assigned')
                        <form method="POST" action="{{ route('courier.assignments.pickup', $asgn) }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Konfirmasi barang sudah diambil?')"
                                    class="text-sm bg-amber-500 text-white px-4 py-2 rounded-xl font-bold hover:bg-amber-600 transition-colors">
                                📦 Tandai Diambil
                            </button>
                        </form>
                        @elseif($asgn->status === 'picked_up')
                        <form method="POST" action="{{ route('courier.assignments.deliver', $asgn) }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Konfirmasi barang sudah dikirim?')"
                                    class="text-sm bg-emerald-500 text-white px-4 py-2 rounded-xl font-bold hover:bg-emerald-600 transition-colors">
                                ✅ Tandai Terkirim
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center mb-6">
    <p class="text-4xl mb-3">🎉</p>
    <p class="font-semibold text-slate-700">Tidak ada tugas hari ini</p>
    <p class="text-sm text-slate-400 mt-1">Nikmati hari Anda!</p>
</div>
@endif

<!-- Recent Assignments -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h2 class="font-bold text-slate-800">📋 Tugas Terbaru</h2>
        <a href="{{ route('courier.assignments.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
    </div>
    <div class="divide-y divide-slate-50">
        @forelse($recentAssignments as $asgn)
        <a href="{{ route('courier.assignments.show', $asgn) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-lg flex-shrink-0">{{ $asgn->donation->category->icon }}</div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-slate-800 text-sm truncate">{{ $asgn->donation->title }}</p>
                <p class="text-xs text-slate-500">Tgl Ambil: {{ $asgn->pickup_date->format('d M Y') }}</p>
            </div>
            @php $c=$asgn->statusColor(); @endphp
            <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700">{{ $asgn->statusLabel() }}</span>
        </a>
        @empty
        <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada tugas</div>
        @endforelse
    </div>
</div>
@endsection
