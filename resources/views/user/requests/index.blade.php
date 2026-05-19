@extends('layouts.app')
@section('title', 'Permintaan Saya')
@section('page-title', 'Permintaan Saya')
@section('sidebar-nav')
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi</p>
    <a href="{{ route('user.catalog.index') }}" class="sidebar-link">🛒 Katalog Donasi</a>
    <a href="{{ route('user.requests.index') }}" class="sidebar-link active">📋 Permintaan Saya</a>
@endsection
@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-3">
        <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            <option value="">Semua Status</option>
            @foreach(['pending'=>'Menunggu','accepted'=>'Diterima','rejected'=>'Ditolak'] as $v=>$l)
            <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">Filter</button>
    </form>
    <a href="{{ route('user.catalog.index') }}" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-colors">
        🛒 Browse Katalog
    </a>
</div>

<div class="space-y-4">
    @forelse($requests as $req)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-start gap-5">
            @if($req->donation->firstPhoto())
                <img src="{{ $req->donation->firstPhoto() }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
            @else
                <div class="w-16 h-16 bg-indigo-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                    {{ $req->donation->category->icon }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $req->donation->title }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $req->donation->category->icon }} {{ $req->donation->category->name }} · {{ $req->donation->conditionLabel() }}</p>
                    </div>
                    @php $rc=$req->statusColor(); @endphp
                    <span class="badge bg-{{ $rc }}-100 text-{{ $rc }}-700 flex-shrink-0">{{ $req->statusLabel() }}</span>
                </div>
                @if($req->message)
                <p class="text-sm text-slate-600 mt-2 p-3 bg-slate-50 rounded-xl">{{ $req->message }}</p>
                @endif
                <div class="mt-3 flex items-center gap-4 text-xs text-slate-400">
                    <span>Diajukan: {{ $req->created_at->format('d M Y H:i') }}</span>
                    @if($req->assignment)
                    <span class="text-indigo-600 font-semibold">🚚 Kurir: {{ $req->assignment->courier->name }}</span>
                    @endif
                </div>
                @if($req->status === 'rejected' && $req->rejection_reason)
                <div class="mt-3 p-3 bg-red-50 rounded-xl">
                    <p class="text-xs text-red-600"><span class="font-semibold">Alasan: </span>{{ $req->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="py-20 text-center">
        <p class="text-5xl mb-4">📋</p>
        <p class="text-slate-500 font-semibold">Belum ada permintaan</p>
        <a href="{{ route('user.catalog.index') }}" class="mt-3 inline-block text-indigo-600 font-semibold hover:underline">Browse katalog →</a>
    </div>
    @endforelse
</div>
<div class="mt-6">{{ $requests->links() }}</div>
@endsection
