@extends('layouts.app')
@section('title', 'Upload Donasi')
@section('page-title', 'Upload Donasi Baru')
@section('page-subtitle', 'Lengkapi informasi barang yang ingin Anda donasikan')
@section('sidebar-nav')
    <a href="{{ route('donor.dashboard') }}" class="sidebar-link">📊 <span>Dashboard</span></a>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-4 pt-4 pb-1">Donasi Saya</p>
    <a href="{{ route('donor.donations.create') }}" class="sidebar-link active">➕ <span>Upload Donasi</span></a>
    <a href="{{ route('donor.donations.index') }}" class="sidebar-link">📦 <span>Donasi Saya</span></a>
@endsection
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <form method="POST" action="{{ route('donor.donations.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Contoh: Kipas angin meja Miyako..."
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none text-sm @error('title') border-red-400 @enderror">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori *</label>
                <select name="category_id" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 outline-none text-sm @error('category_id') border-red-400 @enderror">
                    <option value="">-- Pilih kategori --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Condition -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Kondisi Barang *</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['baru' => 'Baru', 'sangat_baik' => 'Sangat Baik', 'baik' => 'Baik', 'cukup_baik' => 'Cukup Baik'] as $val => $label)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="condition" value="{{ $val }}" class="peer sr-only"
                               {{ old('condition') === $val ? 'checked' : '' }}>
                        <div class="p-3 border-2 border-slate-200 rounded-xl text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                            <p class="text-sm font-semibold text-slate-700">{{ $label }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('condition') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Barang *</label>
                <textarea name="description" rows="4" required minlength="20"
                          placeholder="Ceritakan kondisi, ukuran, kegunaan barang secara detail..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 outline-none text-sm resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Pickup Address -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Pengambilan *</label>
                <input type="text" name="pickup_address" value="{{ old('pickup_address') }}" required
                       placeholder="Kos Melati No. 5, Jl. Pahlawan..."
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-400 outline-none text-sm @error('pickup_address') border-red-400 @enderror">
                @error('pickup_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Photos -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Barang <span class="text-slate-400 font-normal">(maks. 5 foto, 2MB/foto)</span></label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-indigo-400 transition-colors cursor-pointer"
                     onclick="document.getElementById('photos').click()">
                    <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="sr-only"
                           onchange="previewPhotos(event)">
                    <p class="text-4xl mb-2">📸</p>
                    <p class="text-sm text-slate-500">Klik untuk upload foto</p>
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP hingga 2MB</p>
                </div>
                <div id="photo-preview" class="grid grid-cols-4 gap-2 mt-3"></div>
                @error('photos.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-indigo-50 rounded-xl p-4 text-sm text-indigo-700">
                <p class="font-semibold mb-1">ℹ️ Info Proses Donasi</p>
                <p>Donasi Anda akan ditinjau oleh admin terlebih dahulu sebelum ditampilkan di katalog publik. Anda akan mendapat notifikasi setelah verifikasi selesai.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors">
                    📤 Kirim Donasi
                </button>
                <a href="{{ route('donor.donations.index') }}"
                   class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewPhotos(event) {
    const preview = document.getElementById('photo-preview');
    preview.innerHTML = '';
    Array.from(event.target.files).slice(0,5).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-slate-200">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
