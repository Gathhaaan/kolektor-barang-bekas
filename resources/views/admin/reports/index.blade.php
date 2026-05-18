@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan & Statistik')
@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Manajemen</p>
    <a href="{{ route('admin.donations.index') }}" class="sidebar-link">📦 Donasi</a>
    <a href="{{ route('admin.requests.index') }}" class="sidebar-link">🙋 Permintaan</a>
    <a href="{{ route('admin.assignments.index') }}" class="sidebar-link">🚚 Penugasan</a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link">🗂️ Kategori</a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link">👥 Pengguna</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Laporan</p>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link active">📈 Laporan</a>
@endsection
@section('content')

<!-- Year Filter -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex items-center gap-3">
        <label class="text-sm font-semibold text-slate-600">Tahun:</label>
        <select name="year" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none">
            @foreach($availableYears->merge([date('Y')]) as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">Tampilkan</button>
    </form>
</div>

<!-- Status Summary Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    @foreach(['pending'=>['⏳','amber','Menunggu'],'approved'=>['✅','blue','Disetujui'],'in_progress'=>['🚚','purple','Diproses'],'completed'=>['🎉','emerald','Selesai'],'rejected'=>['❌','red','Ditolak']] as $s=>[$icon,$color,$label])
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm text-center">
        <div class="text-3xl mb-2">{{ $icon }}</div>
        <p class="text-2xl font-black text-slate-800">{{ $statusSummary[$s] ?? 0 }}</p>
        <p class="text-xs text-slate-500 mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Monthly Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-slate-800 mb-4">📈 Donasi per Bulan ({{ $year }})</h3>
        <canvas id="monthlyChart" height="200"></canvas>
    </div>

    <!-- Category Breakdown -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-slate-800 mb-4">🗂️ Donasi per Kategori</h3>
        <div class="space-y-3">
            @forelse($categoryStats as $cat)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-slate-700">{{ $cat->icon }} {{ $cat->name }}</span>
                    <span class="text-sm font-bold text-slate-800">{{ $cat->total_donations }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    @php $pct = $categoryStats->max('total_donations') > 0 ? ($cat->total_donations / $categoryStats->max('total_donations') * 100) : 0; @endphp
                    <div class="bg-indigo-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-slate-400 text-sm text-center py-8">Belum ada data</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Top Donors -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-800">🏆 Top Pendonasi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Peringkat</th>
                    <th class="px-6 py-3 text-left">Pendonasi</th>
                    <th class="px-6 py-3 text-left">Total Donasi Selesai</th>
                    <th class="px-6 py-3 text-left">Poin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($topDonors as $i => $donor)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3 text-lg">
                        @if($i===0) 🥇 @elseif($i===1) 🥈 @elseif($i===2) 🥉 @else {{ $i+1 }} @endif
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $donor->avatarUrl() }}" class="w-8 h-8 rounded-full">
                            <div>
                                <p class="font-medium text-slate-800 text-sm">{{ $donor->name }}</p>
                                <p class="text-xs text-slate-500">{{ $donor->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-sm font-semibold text-emerald-600">{{ $donor->completed_count }}</td>
                    <td class="px-6 py-3 text-sm font-bold text-amber-600">⭐ {{ $donor->points }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Total Donasi',
                data: @json($monthlyChart),
                backgroundColor: 'rgba(99,102,241,0.7)',
                borderColor: 'rgba(79,70,229,1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>
@endpush
