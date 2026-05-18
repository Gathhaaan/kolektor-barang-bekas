@extends('layouts.app')

@section('title', 'Detail Donasi')
@section('page-title', 'Detail Donasi')
@section('page-subtitle', 'Tinjau dan kelola donasi ini')

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
<div class="mb-4">
    <a href="{{ route('admin.donations.index') }}" class="text-sm text-slate-500 hover:text-indigo-600">← Kembali ke daftar donasi</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Item Detail Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $donation->title }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="badge bg-indigo-100 text-indigo-700">{{ $donation->category->icon }} {{ $donation->category->name }}</span>
                        <span class="badge bg-slate-100 text-slate-700">{{ $donation->conditionLabel() }}</span>
                        @php $color = $donation->statusColor(); @endphp
                        <span class="badge bg-{{ $color }}-100 text-{{ $color }}-700">{{ $donation->statusLabel() }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-sm max-w-none">
                <p class="text-slate-600 leading-relaxed">{{ $donation->description }}</p>
            </div>

            <div class="mt-4 p-4 bg-slate-50 rounded-xl">
                <p class="text-sm font-semibold text-slate-700 mb-1">📍 Alamat Pengambilan</p>
                <p class="text-sm text-slate-600">{{ $donation->pickup_address }}</p>
            </div>

            @if($donation->rejection_reason)
            <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-200">
                <p class="text-sm font-semibold text-red-700 mb-1">❌ Alasan Penolakan</p>
                <p class="text-sm text-red-600">{{ $donation->rejection_reason }}</p>
            </div>
            @endif

            @if($donation->admin_note)
            <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                <p class="text-sm font-semibold text-blue-700 mb-1">📝 Catatan Admin</p>
                <p class="text-sm text-blue-600">{{ $donation->admin_note }}</p>
            </div>
            @endif
        </div>

        <!-- Photos -->
        @if($donation->photos && count($donation->photos) > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">📷 Foto Barang</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($donation->photos as $photo)
                <img src="{{ asset('storage/' . $photo) }}" alt="Foto donasi"
                     class="w-full h-40 object-cover rounded-xl border border-slate-100">
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex flex-col items-center justify-center h-32 text-slate-300">
                <span class="text-4xl">📷</span>
                <p class="text-sm mt-2">Tidak ada foto</p>
            </div>
        </div>
        @endif

        <!-- Requests -->
        @if($donation->requests->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">🙋 Permintaan Penerima ({{ $donation->requests->count() }})</h3>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach($donation->requests as $req)
                <div class="px-6 py-4 flex items-start gap-4">
                    <img src="{{ $req->user->avatarUrl() }}" class="w-9 h-9 rounded-full flex-shrink-0">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-slate-800 text-sm">{{ $req->user->name }}</p>
                            @php $rc = $req->statusColor(); @endphp
                            <span class="badge bg-{{ $rc }}-100 text-{{ $rc }}-700">{{ $req->statusLabel() }}</span>
                        </div>
                        @if($req->message)
                        <p class="text-sm text-slate-500 mt-1">{{ $req->message }}</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">{{ $req->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Assignment Info -->
        @if($donation->assignment)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">🚚 Info Penugasan Kurir</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-400 mb-1">Kurir</p>
                    <div class="flex items-center gap-2">
                        <img src="{{ $donation->assignment->courier->avatarUrl() }}" class="w-7 h-7 rounded-full">
                        <p class="text-sm font-semibold text-slate-800">{{ $donation->assignment->courier->name }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Tanggal Ambil</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $donation->assignment->pickup_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Status</p>
                    @php $ac = $donation->assignment->statusColor(); @endphp
                    <span class="badge bg-{{ $ac }}-100 text-{{ $ac }}-700">{{ $donation->assignment->statusLabel() }}</span>
                </div>
                @if($donation->assignment->pickup_note)
                <div>
                    <p class="text-xs text-slate-400 mb-1">Catatan Pengambilan</p>
                    <p class="text-sm text-slate-600">{{ $donation->assignment->pickup_note }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-6">
        <!-- Donor Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">👤 Informasi Pendonasi</h3>
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $donation->user->avatarUrl() }}" class="w-12 h-12 rounded-full">
                <div>
                    <p class="font-semibold text-slate-800">{{ $donation->user->name }}</p>
                    <p class="text-xs text-slate-500">{{ $donation->user->email }}</p>
                </div>
            </div>
            @if($donation->user->phone)
            <p class="text-sm text-slate-600">📱 {{ $donation->user->phone }}</p>
            @endif
            <p class="text-xs text-slate-400 mt-2">Diupload: {{ $donation->created_at->format('d M Y H:i') }}</p>
        </div>

        <!-- Actions -->
        @if($donation->status === 'pending')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
            <h3 class="font-bold text-slate-800">⚡ Tindakan</h3>

            <!-- Approve -->
            <form method="POST" action="{{ route('admin.donations.approve', $donation) }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Admin (opsional)</label>
                    <textarea name="admin_note" rows="2" placeholder="Catatan untuk donor..."
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-400"></textarea>
                </div>
                <button type="submit" onclick="return confirm('Setujui donasi ini?')"
                        class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold transition-colors">
                    ✅ Setujui Donasi
                </button>
            </form>

            <div class="border-t border-slate-100 pt-4">
                <!-- Reject -->
                <form method="POST" action="{{ route('admin.donations.reject', $donation) }}" x-data="{ show: false }">
                    @csrf
                    <button type="button" @click="show = !show"
                            class="w-full py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm font-bold transition-colors mb-3">
                        ❌ Tolak Donasi
                    </button>
                    <div x-show="show" x-cloak>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Alasan penolakan *</label>
                        <textarea name="rejection_reason" rows="3" required
                                  placeholder="Jelaskan alasan penolakan..."
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-red-400 mb-2"></textarea>
                        <button type="submit" onclick="return confirm('Tolak donasi ini?')"
                                class="w-full py-2 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 transition-colors">
                            Konfirmasi Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Assign Courier -->
        @if($donation->status === 'approved')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">🚚 Tugaskan Kurir</h3>
            
            @if($donation->requests->where('status','pending')->isEmpty())
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                <p class="text-sm font-semibold text-amber-700 mb-1">Menunggu Permintaan</p>
                <p class="text-sm text-amber-600">Kurir baru dapat ditugaskan setelah ada penerima yang mengajukan permintaan untuk barang ini.</p>
            </div>
            @else
            <form method="POST" action="{{ route('admin.donations.assign', $donation) }}" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Permintaan *</label>
                    <select name="request_id" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
                        <option value="">-- Pilih permintaan penerima --</option>
                        @foreach($donation->requests->where('status','pending') as $req)
                        <option value="{{ $req->id }}">{{ $req->user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Kurir *</label>
                    <select name="courier_id" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
                        <option value="">-- Pilih kurir --</option>
                        @foreach($couriers as $courier)
                        <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Pengambilan *</label>
                    <input type="date" name="pickup_date" required min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan (opsional)</label>
                    <textarea name="pickup_note" rows="2" placeholder="Instruksi untuk kurir..."
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400"></textarea>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold transition-colors">
                    🚚 Tugaskan Kurir
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
