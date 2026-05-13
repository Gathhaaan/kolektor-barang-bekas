@extends('layouts.app')
@section('title', 'Detail Tugas')
@section('page-title', 'Detail Penugasan')
@section('sidebar-nav')
    <a href="{{ route('courier.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Tugas</p>
    <a href="{{ route('courier.assignments.index') }}" class="sidebar-link active">🚚 Penugasan Saya</a>
@endsection
@section('content')
<div class="mb-4"><a href="{{ route('courier.assignments.index') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Kembali</a></div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Item Info -->
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800">📦 Info Barang</h3>
                @php $c=$assignment->statusColor(); @endphp
                <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700">{{ $assignment->statusLabel() }}</span>
            </div>
            <h2 class="text-xl font-black text-slate-800 mb-2">{{ $assignment->donation->title }}</h2>
            <div class="flex items-center gap-2 mb-3">
                <span class="badge bg-indigo-100 text-indigo-700">{{ $assignment->donation->category->icon }} {{ $assignment->donation->category->name }}</span>
                <span class="badge bg-slate-100 text-slate-700">{{ $assignment->donation->conditionLabel() }}</span>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">{{ $assignment->donation->description }}</p>
            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-xs font-bold text-amber-700 mb-1">📍 ALAMAT PENGAMBILAN</p>
                <p class="text-sm text-amber-800 font-semibold">{{ $assignment->donation->pickup_address }}</p>
            </div>
        </div>

        <!-- Donor -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-3">👤 Pendonasi</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $assignment->donation->donor->avatarUrl() }}" class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold text-slate-800">{{ $assignment->donation->donor->name }}</p>
                    @if($assignment->donation->donor->phone)
                    <p class="text-sm text-indigo-600">📱 {{ $assignment->donation->donor->phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recipient -->
        @if($assignment->request)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-3">🙋 Penerima</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $assignment->request->recipient->avatarUrl() }}" class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold text-slate-800">{{ $assignment->request->recipient->name }}</p>
                    @if($assignment->request->recipient->phone)
                    <p class="text-sm text-indigo-600">📱 {{ $assignment->request->recipient->phone }}</p>
                    @endif
                    @if($assignment->request->recipient->address)
                    <p class="text-xs text-slate-500 mt-0.5">📍 {{ $assignment->request->recipient->address }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">📋 Info Penugasan</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-xs text-slate-400">Tanggal Ambil</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $assignment->pickup_date->format('d M Y') }}</p>
                </div>
                @if($assignment->picked_up_at)
                <div>
                    <p class="text-xs text-slate-400">Waktu Diambil</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $assignment->picked_up_at->format('d M Y H:i') }}</p>
                </div>
                @endif
                @if($assignment->delivered_at)
                <div>
                    <p class="text-xs text-slate-400">Waktu Dikirim</p>
                    <p class="text-sm font-semibold text-emerald-700">{{ $assignment->delivered_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>

            @if($assignment->pickup_note)
            <div class="p-3 bg-slate-50 rounded-xl mb-4">
                <p class="text-xs font-semibold text-slate-600 mb-1">Catatan dari Admin</p>
                <p class="text-sm text-slate-700">{{ $assignment->pickup_note }}</p>
            </div>
            @endif
        </div>

        <!-- Update Status Actions -->
        @if($assignment->status === 'assigned')
        <div class="bg-white rounded-2xl shadow-sm border border-amber-200 border-2 p-6">
            <h3 class="font-bold text-slate-800 mb-4">📦 Update Status Pengambilan</h3>
            <form method="POST" action="{{ route('courier.assignments.pickup', $assignment) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Pengambilan (opsional)</label>
                    <textarea name="pickup_note" rows="2" placeholder="Kondisi saat pengambilan..."
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-amber-400 resize-none"></textarea>
                </div>
                <button type="submit" onclick="return confirm('Konfirmasi barang sudah diambil?')"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors">
                    📦 Konfirmasi Barang Sudah Diambil
                </button>
            </form>
        </div>
        @elseif($assignment->status === 'picked_up')
        <div class="bg-white rounded-2xl shadow-sm border-2 border-emerald-200 p-6">
            <h3 class="font-bold text-slate-800 mb-4">✅ Update Status Pengiriman</h3>
            <form method="POST" action="{{ route('courier.assignments.deliver', $assignment) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Pengiriman (opsional)</label>
                    <textarea name="delivery_note" rows="2" placeholder="Kondisi saat pengiriman..."
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-400 resize-none"></textarea>
                </div>
                <button type="submit" onclick="return confirm('Konfirmasi barang sudah dikirim ke penerima?')"
                        class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-colors">
                    ✅ Konfirmasi Barang Sudah Dikirim
                </button>
            </form>
        </div>
        @elseif($assignment->status === 'delivered')
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 text-center">
            <p class="text-4xl mb-3">🎉</p>
            <p class="font-bold text-emerald-700">Tugas Selesai!</p>
            <p class="text-sm text-emerald-600 mt-1">Pengiriman berhasil diselesaikan.</p>
        </div>
        @endif
    </div>
</div>
@endsection
