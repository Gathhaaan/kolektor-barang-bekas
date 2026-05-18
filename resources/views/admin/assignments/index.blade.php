@extends('layouts.app')
@section('title', 'Manajemen Penugasan')
@section('page-title', 'Penugasan Kurir')
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
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            <option value="">Semua Status</option>
            @foreach(['assigned'=>'Ditugaskan','picked_up'=>'Diambil','delivered'=>'Dikirim'] as $v=>$l)
                <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">Filter</button>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left font-semibold">Barang</th>
                    <th class="px-6 py-3 text-left font-semibold">Kurir</th>
                    <th class="px-6 py-3 text-left font-semibold">Tgl Ambil</th>
                    <th class="px-6 py-3 text-left font-semibold">Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($assignments as $asgn)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3">
                        <p class="font-medium text-sm text-slate-800">{{ $asgn->donation->title }}</p>
                        <p class="text-xs text-slate-500">{{ $asgn->donation->category->icon }} {{ $asgn->donation->category->name }}</p>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <img src="{{ $asgn->courier->avatarUrl() }}" class="w-7 h-7 rounded-full">
                            <span class="text-sm text-slate-700">{{ $asgn->courier->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-600">{{ $asgn->pickup_date->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        @php $c=$asgn->statusColor(); @endphp
                        <span class="badge bg-{{ $c }}-100 text-{{ $c }}-700">{{ $asgn->statusLabel() }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.assignments.show', $asgn) }}" class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg font-medium hover:bg-indigo-100">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Tidak ada penugasan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">{{ $assignments->links() }}</div>
</div>
@endsection
