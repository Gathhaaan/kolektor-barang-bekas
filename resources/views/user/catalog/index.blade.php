@extends('layouts.app')
@section('title', 'Katalog Donasi')
@section('page-title', 'Katalog Donasi')
@section('page-subtitle', 'Browse barang yang tersedia untuk diambil')
@section('sidebar-nav')
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi</p>
    <a href="{{ route('user.catalog.index') }}" class="sidebar-link active">🛒 Katalog Donasi</a>
    <a href="{{ route('user.requests.index') }}" class="sidebar-link">📋 Permintaan Saya</a>
@endsection
@section('content')
<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..."
               class="flex-1 min-w-48 px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
        <select name="category" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->icon }} {{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="condition" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            <option value="">Semua Kondisi</option>
            @foreach(['baru'=>'Baru','sangat_baik'=>'Sangat Baik','baik'=>'Baik','cukup_baik'=>'Cukup Baik'] as $v=>$l)
            <option value="{{ $v }}" {{ request('condition')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">🔍 Cari</button>
        @if(request()->hasAny(['search','category','condition']))
        <a href="{{ route('user.catalog.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm">Reset</a>
        @endif
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($donations as $donation)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow group">
        @if($donation->firstPhoto())
            <img src="{{ $donation->firstPhoto() }}" alt="{{ $donation->title }}" class="w-full h-44 object-cover group-hover:opacity-95 transition-opacity">
        @else
            <div class="w-full h-44 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                <span class="text-5xl">{{ $donation->category->icon }}</span>
            </div>
        @endif
        <div class="p-5">
            <div class="flex items-center gap-2 mb-2">
                <span class="badge bg-indigo-100 text-indigo-700 text-xs">{{ $donation->category->name }}</span>
                <span class="badge bg-slate-100 text-slate-600 text-xs">{{ $donation->conditionLabel() }}</span>
            </div>
            <h3 class="font-bold text-slate-800 truncate">{{ $donation->title }}</h3>
            <p class="text-sm text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $donation->description }}</p>
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ $donation->user->avatarUrl() }}" class="w-6 h-6 rounded-full">
                    <span class="text-xs text-slate-500">{{ $donation->user->name }}</span>
                </div>
                <a href="{{ route('user.catalog.show', $donation) }}"
                   class="text-xs bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-indigo-700 transition-colors">
                    Minta →
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-20 text-center">
        <p class="text-5xl mb-4">🔍</p>
        <p class="text-slate-500 font-semibold">Tidak ada barang yang cocok</p>
        <p class="text-slate-400 text-sm mt-1">Coba ubah filter pencarian</p>
    </div>
    @endforelse
</div>
<div class="mt-6">{{ $donations->links() }}</div>
@endsection
