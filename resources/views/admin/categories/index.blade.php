@extends('layouts.app')
@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori')
@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">📊 Dashboard</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Manajemen</p>
    <a href="{{ route('admin.donations.index') }}" class="sidebar-link">📦 Donasi</a>
    <a href="{{ route('admin.assignments.index') }}" class="sidebar-link">🚚 Penugasan</a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link active">🗂️ Kategori</a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link">👥 Pengguna</a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Laporan</p>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-link">📈 Laporan</a>
@endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-4">➕ Tambah Kategori</h3>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kategori *</label>
                    <input type="text" name="name" required placeholder="Nama kategori..."
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Emoji/Ikon</label>
                    <input type="text" name="icon" placeholder="📦" maxlength="4"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi (opsional)</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat..."
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-indigo-400"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-colors">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Category List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">📂 Daftar Kategori ({{ $categories->count() }})</h3>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($categories as $cat)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">{{ $cat->icon }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800">{{ $cat->name }}</p>
                        <p class="text-xs text-slate-500">{{ $cat->description }}</p>
                        <p class="text-xs text-indigo-600 mt-0.5">{{ $cat->total_donations ?? 0 }} donasi</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Edit -->
                        <button onclick="document.getElementById('edit-{{ $cat->id }}').classList.toggle('hidden')"
                                class="text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg hover:bg-slate-200 font-medium">Edit</button>
                        <!-- Delete -->
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                    class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 font-medium">Hapus</button>
                        </form>
                    </div>
                </div>
                <!-- Edit Form -->
                <div id="edit-{{ $cat->id }}" class="hidden px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <form method="POST" action="{{ route('admin.categories.update', $cat) }}" class="flex flex-wrap gap-3 items-end">
                        @csrf @method('PUT')
                        <div class="flex-1 min-w-32">
                            <label class="text-xs font-semibold text-slate-600">Nama</label>
                            <input type="text" name="name" value="{{ $cat->name }}" required
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none mt-1">
                        </div>
                        <div class="w-20">
                            <label class="text-xs font-semibold text-slate-600">Ikon</label>
                            <input type="text" name="icon" value="{{ $cat->icon }}" maxlength="4"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none mt-1">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors">
                            Simpan
                        </button>
                    </form>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-slate-400">Belum ada kategori</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
