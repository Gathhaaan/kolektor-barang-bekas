@extends('layouts.app')
@section('title', 'Detail Donasi')
@section('page-title', 'Detail Donasi')
@section('sidebar-nav')
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi Saya</p>
    <a href="{{ route('user.donations.create') }}" class="sidebar-link">➕ Upload Donasi</a>
    <a href="{{ route('user.donations.index') }}" class="sidebar-link active">📦 Donasi Saya</a>
@endsection
@section('content')
<div class="mb-4"><a href="{{ route('user.donations.index') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Kembali</a></div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $donation->title }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge bg-indigo-100 text-indigo-700">{{ $donation->category->icon }} {{ $donation->category->name }}</span>
                        <span class="badge bg-slate-100 text-slate-700">{{ $donation->conditionLabel() }}</span>
                        @php $c=$donation->statusColor(); @endphp
                        <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700">{{ $donation->statusLabel() }}</span>
                    </div>
                </div>
            </div>
            <p class="text-slate-600 leading-relaxed">{{ $donation->description }}</p>
            <div class="mt-4 p-4 bg-slate-50 rounded-xl">
                <p class="text-xs font-semibold text-slate-600 mb-1">📍 Alamat Pengambilan</p>
                <p class="text-sm text-slate-700">{{ $donation->pickup_address }}</p>
            </div>
            @if($donation->rejection_reason)
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-xs font-semibold text-red-700 mb-1">❌ Alasan Penolakan</p>
                <p class="text-sm text-red-600">{{ $donation->rejection_reason }}</p>
            </div>
            @endif
            @if($donation->admin_note)
            <div class="mt-4 p-4 bg-blue-50 rounded-xl">
                <p class="text-xs font-semibold text-blue-700 mb-1">💬 Catatan Admin</p>
                <p class="text-sm text-blue-600">{{ $donation->admin_note }}</p>
            </div>
            @endif
        </div>

        @if($donation->photos && count($donation->photos) > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">📷 Foto Barang</h3>
            <div class="grid grid-cols-3 gap-3">
                @foreach($donation->photos as $photo)
                <img src="{{ asset('storage/'.$photo) }}" class="w-full h-40 object-cover rounded-xl border border-slate-100">
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-5">
        <!-- Status Timeline -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">📊 Status Donasi</h3>
            @php
                $steps = ['pending','approved','assigned','picked_up','delivered','completed'];
                $currentIdx = array_search($donation->status, $steps);
                $currentIdx = $currentIdx === false ? ($donation->status==='rejected'?-1:0) : $currentIdx;
            @endphp
            @if($donation->status === 'rejected')
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl">
                    <span class="text-2xl">❌</span>
                    <div>
                        <p class="font-semibold text-red-700">Donasi Ditolak</p>
                        <p class="text-xs text-red-500">Lihat alasan penolakan di atas</p>
                    </div>
                </div>
            @else
            <div class="space-y-3">
                @foreach(['pending'=>['⏳','Menunggu Verifikasi'],'approved'=>['✅','Disetujui'],'assigned'=>['🚚','Kurir Ditugaskan'],'picked_up'=>['📦','Barang Diambil'],'delivered'=>['🎉','Selesai Dikirim'],'completed'=>['⭐','Selesai & Poin Didapat']] as $s=>[$icon,$label])
                @php $idx=array_search($s,$steps); $done=$idx<=$currentIdx; @endphp
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm flex-shrink-0 {{ $done ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                        {{ $done ? $icon : '○' }}
                    </div>
                    <p class="text-sm {{ $done ? 'font-semibold text-slate-800' : 'text-slate-400' }}">{{ $label }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Courier Info -->
        @if($donation->assignment)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-3">🚚 Informasi Kurir</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $donation->assignment->courier->avatarUrl() }}" class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold text-slate-800">{{ $donation->assignment->courier->name }}</p>
                    <p class="text-xs text-slate-500">{{ $donation->assignment->courier->phone }}</p>
                </div>
            </div>
            <div class="mt-3 text-sm text-slate-600">
                <p>Tgl Ambil: <span class="font-semibold">{{ $donation->assignment->pickup_date->format('d M Y') }}</span></p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
