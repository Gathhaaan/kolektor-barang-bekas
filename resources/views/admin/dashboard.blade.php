@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan aktivitas donasi platform')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        📊 <span>Dashboard</span>
    </a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Manajemen</p>
    <a href="{{ route('admin.donations.index') }}" class="sidebar-link {{ request()->routeIs('admin.donations*') ? 'active' : '' }}">
        📦 <span>Donasi</span>
        @if($stats['pending'] > 0)
            <span class="ml-auto bg-amber-400 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
        @endif
    </a>
    <a href="{{ route('admin.requests.index') }}" class="sidebar-link {{ request()->routeIs('admin.requests*') ? 'active' : '' }}">
        🙋 <span>Permintaan</span>
        @php $pendingRequestsCount = \App\Models\DonationRequest::where('status', 'pending')->count(); @endphp
        @if($pendingRequestsCount > 0)
            <span class="ml-auto bg-amber-400 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $pendingRequestsCount }}</span>
        @endif
    </a>
    <a href="{{ route('admin.assignments.index') }}" class="sidebar-link {{ request()->routeIs('admin.assignments*') ? 'active' : '' }}">
        🚚 <span>Penugasan</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        🗂️ <span>Kategori</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        👥 <span>Pengguna</span>
    </a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Laporan</p>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
        📈 <span>Laporan</span>
    </a>
@endsection

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-xl">⏳</div>
            <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-full">Perlu Tindakan</span>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $stats['pending'] }}</p>
        <p class="text-sm text-slate-500 mt-1">Menunggu Verifikasi</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">✅</div>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $stats['approved'] }}</p>
        <p class="text-sm text-slate-500 mt-1">Disetujui / Aktif</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-xl">🚚</div>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $stats['assigned'] }}</p>
        <p class="text-sm text-slate-500 mt-1">Dalam Proses Antar</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-xl">🎉</div>
        </div>
        <p class="text-3xl font-black text-slate-800">{{ $stats['completed'] }}</p>
        <p class="text-sm text-slate-500 mt-1">Selesai</p>
    </div>
</div>

<!-- User Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-5 text-white">
        <p class="text-4xl font-black">{{ $stats['donors'] }}</p>
        <p class="text-indigo-200 text-sm mt-1">📤 Pendonasi Terdaftar</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 text-white">
        <p class="text-4xl font-black">{{ $stats['recipients'] }}</p>
        <p class="text-emerald-200 text-sm mt-1">🙋 Penerima Terdaftar</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-5 text-white">
        <p class="text-4xl font-black">{{ $stats['couriers'] }}</p>
        <p class="text-purple-200 text-sm mt-1">🚚 Kurir Aktif</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pending Verifications -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">⏳ Antrian Verifikasi</h2>
            <a href="{{ route('admin.donations.index', ['status' => 'pending']) }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($pendingDonations as $donation)
            <a href="{{ route('admin.donations.show', $donation) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
                    {{ $donation->category->icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $donation->title }}</p>
                    <p class="text-xs text-slate-500">{{ $donation->donor->name }} · {{ $donation->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-lg font-medium">Tinjau →</span>
            </a>
            @empty
            <div class="px-6 py-8 text-center text-slate-400 text-sm">
                🎉 Tidak ada donasi yang menunggu verifikasi
            </div>
            @endforelse
        </div>
    </div>

    <!-- Active Assignments -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">🚚 Pengiriman Aktif</h2>
            <a href="{{ route('admin.assignments.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($activeAssignments as $assignment)
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-lg flex-shrink-0">🚚</div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $assignment->donation->title }}</p>
                    <p class="text-xs text-slate-500">Kurir: {{ $assignment->courier->name }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-lg font-medium
                    {{ $assignment->status === 'assigned' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $assignment->statusLabel() }}
                </span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-400 text-sm">Tidak ada pengiriman aktif</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Donations Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 mt-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h2 class="font-bold text-slate-800">📋 Donasi Terbaru</h2>
        <a href="{{ route('admin.donations.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left font-semibold">Barang</th>
                    <th class="px-6 py-3 text-left font-semibold">Pendonasi</th>
                    <th class="px-6 py-3 text-left font-semibold">Kategori</th>
                    <th class="px-6 py-3 text-left font-semibold">Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($recentDonations as $donation)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.donations.show', $donation) }}" class="font-medium text-slate-800 hover:text-indigo-600 text-sm">
                            {{ $donation->title }}
                        </a>
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-600">{{ $donation->donor->name }}</td>
                    <td class="px-6 py-3">
                        <span class="text-sm">{{ $donation->category->icon }} {{ $donation->category->name }}</span>
                    </td>
                    <td class="px-6 py-3">
                        @php $color = $donation->statusColor(); @endphp
                        <span class="badge bg-{{ $color }}-100 text-{{ $color }}-700">{{ $donation->statusLabel() }}</span>
                    </td>
                    <td class="px-6 py-3 text-xs text-slate-500">{{ $donation->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
