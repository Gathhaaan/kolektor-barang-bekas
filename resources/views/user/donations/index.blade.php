@extends('layouts.app')
@section('title', 'Donasi Saya')
@section('page-title', 'Donasi Saya')
@section('sidebar-nav')
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">📊 <span>Dashboard</span></a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi Saya</p>
    <a href="{{ route('user.donations.create') }}" class="sidebar-link">➕ <span>Upload Donasi</span></a>
    <a href="{{ route('user.donations.index') }}" class="sidebar-link active">📦 <span>Donasi Saya</span></a>
@endsection
@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-3">
        <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            <option value="">Semua Status</option>
            @foreach(['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak','assigned'=>'Ditugaskan','completed'=>'Selesai'] as $v=>$l)
                <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">Filter</button>
    </form>
    <a href="{{ route('user.donations.create') }}" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-colors">
        ➕ Upload Baru
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($donations as $donation)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
        @if($donation->firstPhoto())
            <img src="{{ $donation->firstPhoto() }}" alt="{{ $donation->title }}" class="w-full h-40 object-cover">
        @else
            <div class="w-full h-40 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                <span class="text-5xl">{{ $donation->category->icon }}</span>
            </div>
        @endif
        <div class="p-5">
            <div class="flex items-center gap-2 mb-2">
                @php $c=$donation->statusColor(); @endphp
                <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700">{{ $donation->statusLabel() }}</span>
                <span class="badge bg-slate-100 text-slate-600">{{ $donation->conditionLabel() }}</span>
            </div>
            <h3 class="font-bold text-slate-800 truncate">{{ $donation->title }}</h3>
            <p class="text-xs text-slate-500 mt-1">{{ $donation->category->icon }} {{ $donation->category->name }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $donation->created_at->format('d M Y') }}</p>
            <a href="{{ route('user.donations.show', $donation) }}"
               class="mt-4 block text-center py-2 bg-indigo-50 text-indigo-700 rounded-xl text-sm font-semibold hover:bg-indigo-100 transition-colors">
                Lihat Detail →
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-16 text-center">
        <p class="text-5xl mb-4">📦</p>
        <p class="text-slate-500">Belum ada donasi</p>
        <a href="{{ route('user.donations.create') }}" class="mt-3 inline-block text-indigo-600 font-semibold hover:underline">Upload donasi pertama Anda →</a>
    </div>
    @endforelse
</div>
<div class="mt-6">{{ $donations->links() }}</div>
@endsection
