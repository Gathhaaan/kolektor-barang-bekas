@extends('layouts.app')

@section('title', 'Manajemen Donasi')
@section('page-title', 'Manajemen Donasi')
@section('page-subtitle', 'Verifikasi dan kelola semua donasi')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">📊 <span>Dashboard</span></a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Manajemen</p>
    <a href="{{ route('admin.donations.index') }}" class="sidebar-link active">📦 <span>Donasi</span></a>
    <a href="{{ route('admin.requests.index') }}" class="sidebar-link">🙋 <span>Permintaan</span></a>
    <a href="{{ route('admin.assignments.index') }}" class="sidebar-link">🚚 <span>Penugasan</span></a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link">🗂️ <span>Kategori</span></a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link">👥 <span>Pengguna</span></a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Laporan</p>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link">📈 <span>Laporan</span></a>
@endsection

@section('content')
<!-- Filters -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..."
               class="flex-1 min-w-48 px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
        <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
            <option value="">Semua Status</option>
            @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'assigned' => 'Ditugaskan', 'picked_up' => 'Diambil', 'delivered' => 'Dikirim', 'completed' => 'Selesai'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors">
            🔍 Filter
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.donations.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm hover:bg-slate-200 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left font-semibold">Barang</th>
                    <th class="px-6 py-3 text-left font-semibold">Pendonasi</th>
                    <th class="px-6 py-3 text-left font-semibold">Kategori</th>
                    <th class="px-6 py-3 text-left font-semibold">Kondisi</th>
                    <th class="px-6 py-3 text-left font-semibold">Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($donations as $donation)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($donation->firstPhoto())
                                <img src="{{ $donation->firstPhoto() }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-lg flex-shrink-0">{{ $donation->category->icon }}</div>
                            @endif
                            <span class="font-medium text-slate-800 text-sm">{{ $donation->title }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-600">{{ $donation->donor->name }}</td>
                    <td class="px-6 py-3 text-sm text-slate-600">{{ $donation->category->icon }} {{ $donation->category->name }}</td>
                    <td class="px-6 py-3">
                        <span class="badge bg-slate-100 text-slate-700">{{ $donation->conditionLabel() }}</span>
                    </td>
                    <td class="px-6 py-3">
                        @php $color = $donation->statusColor(); @endphp
                        <span class="badge bg-{{ $color }}-100 text-{{ $color }}-700">{{ $donation->statusLabel() }}</span>
                    </td>
                    <td class="px-6 py-3 text-xs text-slate-500">{{ $donation->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.donations.show', $donation) }}"
                           class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition-colors font-medium">
                            Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">Tidak ada donasi ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $donations->links() }}
    </div>
</div>
@endsection
