@extends('layouts.app')
@section('title', 'Detail Penugasan')
@section('page-title', 'Detail Penugasan')
@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Manajemen</p>
    <a href="{{ route('admin.donations.index') }}" class="sidebar-link">📦 Donasi</a>
    <a href="{{ route('admin.requests.index') }}" class="sidebar-link">🙋 Permintaan</a>
    <a href="{{ route('admin.assignments.index') }}" class="sidebar-link active">🚚 Penugasan</a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link">🗂️ Kategori</a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link">👥 Pengguna</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Laporan</p>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link">📈 Laporan</a>
@endsection
@section('content')
<div class="mb-4"><a href="{{ route('admin.assignments.index') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Kembali</a></div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
        <h3 class="font-bold text-slate-800">📦 Info Donasi</h3>
        <div>
            <p class="text-xs text-slate-400">Nama Barang</p>
            <p class="font-semibold text-slate-800">{{ $assignment->donation->title }}</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-400">Kategori</p>
                <p class="text-sm text-slate-700">{{ $assignment->donation->category->icon }} {{ $assignment->donation->category->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Kondisi</p>
                <p class="text-sm text-slate-700">{{ $assignment->donation->conditionLabel() }}</p>
            </div>
        </div>
        <div>
            <p class="text-xs text-slate-400">Alamat Pengambilan</p>
            <p class="text-sm text-slate-700">{{ $assignment->donation->pickup_address }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400">Pendonasi</p>
            <div class="flex items-center gap-2 mt-1">
                <img src="{{ $assignment->donation->user->avatarUrl() }}" class="w-7 h-7 rounded-full">
                <span class="text-sm font-semibold">{{ $assignment->donation->user->name }}</span>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">🚚 Info Pengiriman</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-400">Kurir</p>
                    <div class="flex items-center gap-2 mt-1">
                        <img src="{{ $assignment->courier->avatarUrl() }}" class="w-7 h-7 rounded-full">
                        <span class="text-sm font-semibold">{{ $assignment->courier->name }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Status</p>
                    @php $c=$assignment->statusColor(); @endphp
                    <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700 mt-1">{{ $assignment->statusLabel() }}</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Tgl Pengambilan</p>
                    <p class="text-sm font-semibold">{{ $assignment->pickup_date->format('d M Y') }}</p>
                </div>
                @if($assignment->delivery_date)
                <div>
                    <p class="text-xs text-slate-400">Tgl Pengiriman</p>
                    <p class="text-sm font-semibold">{{ $assignment->delivery_date->format('d M Y') }}</p>
                </div>
                @endif
            </div>
            @if($assignment->pickup_note)
            <div class="mt-4 p-3 bg-slate-50 rounded-xl">
                <p class="text-xs font-semibold text-slate-600 mb-1">Catatan Pengambilan</p>
                <p class="text-sm text-slate-600">{{ $assignment->pickup_note }}</p>
            </div>
            @endif
            @if($assignment->delivery_note)
            <div class="mt-3 p-3 bg-emerald-50 rounded-xl">
                <p class="text-xs font-semibold text-emerald-700 mb-1">Catatan Pengiriman</p>
                <p class="text-sm text-emerald-700">{{ $assignment->delivery_note }}</p>
            </div>
            @endif
        </div>

        @if($assignment->request)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-3">🙋 Penerima</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $assignment->request->user->avatarUrl() }}" class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold text-slate-800">{{ $assignment->request->user->name }}</p>
                    <p class="text-xs text-slate-500">{{ $assignment->request->user->email }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
