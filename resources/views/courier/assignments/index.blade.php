@extends('layouts.app')
@section('title', 'Penugasan Saya')
@section('page-title', 'Penugasan Saya')
@section('sidebar-nav')
    <a href="{{ route('courier.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Tugas</p>
    <a href="{{ route('courier.assignments.index') }}" class="sidebar-link active">🚚 Penugasan Saya</a>
@endsection
@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            <option value="">Semua Status</option>
            @foreach(['assigned'=>'Ditugaskan','picked_up'=>'Sudah Diambil','delivered'=>'Sudah Dikirim'] as $v=>$l)
            <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">Filter</button>
    </form>
</div>

<div class="space-y-4">
    @forelse($assignments as $asgn)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                {{ $asgn->donation->category->icon }}
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $asgn->donation->title }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $asgn->donation->category->name }} · {{ $asgn->donation->conditionLabel() }}</p>
                    </div>
                    @php $c=$asgn->statusColor(); @endphp
                    <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700">{{ $asgn->statusLabel() }}</span>
                </div>
                <p class="text-sm text-slate-600 mt-2">📍 {{ $asgn->donation->pickup_address }}</p>
                <p class="text-xs text-slate-400 mt-1">Tgl Ambil: {{ $asgn->pickup_date->format('d M Y') }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('courier.assignments.show', $asgn) }}" class="text-sm bg-slate-100 text-slate-700 px-4 py-2 rounded-xl font-semibold hover:bg-slate-200 transition-colors">Detail</a>
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
    @empty
    <div class="py-20 text-center">
        <p class="text-5xl mb-4">🚚</p>
        <p class="text-slate-500 font-semibold">Belum ada penugasan</p>
    </div>
    @endforelse
</div>
<div class="mt-6">{{ $assignments->links() }}</div>
@endsection
