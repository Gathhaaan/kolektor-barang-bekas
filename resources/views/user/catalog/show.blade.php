@extends('layouts.app')
@section('title', $donation->title)
@section('page-title', 'Detail Barang')
@section('sidebar-nav')
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi</p>
    <a href="{{ route('user.catalog.index') }}" class="sidebar-link active">🛒 Katalog Donasi</a>
    <a href="{{ route('user.requests.index') }}" class="sidebar-link">📋 Permintaan Saya</a>
@endsection
@section('content')
<div class="mb-4"><a href="{{ route('user.catalog.index') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Kembali ke katalog</a></div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        <!-- Photos -->
        @if($donation->photos && count($donation->photos) > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="grid grid-cols-2 gap-3">
                @foreach($donation->photos as $i => $photo)
                <img src="{{ asset('storage/'.$photo) }}" class="w-full {{ $i===0 ? 'col-span-2 h-72' : 'h-40' }} object-cover rounded-xl border border-slate-100">
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl h-60 flex items-center justify-center">
            <span class="text-8xl">{{ $donation->category->icon }}</span>
        </div>
        @endif

        <!-- Item Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="badge bg-indigo-100 text-indigo-700">{{ $donation->category->icon }} {{ $donation->category->name }}</span>
                <span class="badge bg-slate-100 text-slate-700">{{ $donation->conditionLabel() }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 mb-3">{{ $donation->title }}</h1>
            <p class="text-slate-600 leading-relaxed">{{ $donation->description }}</p>
            <div class="mt-4 p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-semibold text-slate-600 mb-1">📍 Lokasi Pengambilan</p>
                <p class="text-sm text-slate-700">{{ $donation->pickup_address }}</p>
            </div>
        </div>
    </div>

    <!-- Request Sidebar -->
    <div class="space-y-5">
        <!-- Donor Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-3">👤 Pendonasi</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $donation->user->avatarUrl() }}" class="w-12 h-12 rounded-full">
                <div>
                    <p class="font-semibold text-slate-800">{{ $donation->user->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $donation->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Request Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">🙋 Ajukan Permintaan</h3>

            @if($donation->user_id === auth()->id())
            <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl text-center">
                <p class="text-2xl mb-2">🏷️</p>
                <p class="text-sm font-semibold text-indigo-700">Ini adalah barang donasi Anda sendiri.</p>
                <a href="{{ route('user.donations.show', $donation) }}" class="mt-2 inline-block text-xs text-indigo-600 hover:underline">Lihat detail donasi</a>
            </div>
            @elseif($alreadyRequested)
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-center">
                <p class="text-2xl mb-2">📋</p>
                <p class="text-sm font-semibold text-amber-700">Anda sudah mengajukan permintaan untuk barang ini</p>
                <a href="{{ route('user.requests.index') }}" class="mt-2 inline-block text-xs text-indigo-600 hover:underline">Lihat permintaan saya</a>
            </div>
            @else
            <form method="POST" action="{{ route('user.requests.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="donation_id" value="{{ $donation->id }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-2">Pesan (opsional)</label>
                    <textarea name="message" rows="3"
                              placeholder="Ceritakan mengapa Anda membutuhkan barang ini..."
                              class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400 resize-none">{{ old('message') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors">
                    🙋 Ajukan Permintaan
                </button>
            </form>
            <p class="text-xs text-slate-400 mt-3 text-center leading-relaxed">
                Permintaan Anda akan ditinjau oleh admin sebelum dikonfirmasi
            </p>
            @endif
        </div>
    </div>
</div>
@endsection
