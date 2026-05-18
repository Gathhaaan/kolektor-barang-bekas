<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kolektor Barang Bekas - Platform donasi barang kampus yang terorganisir untuk mahasiswa">
    <title>Kolektor Barang Bekas — Platform Donasi Kampus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>
        .gradient-hero { background: linear-gradient(135deg, #312e81 0%, #4f46e5 50%, #6366f1 100%); }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

<!-- Navbar -->
<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-xl flex items-center justify-center shadow">
                <span class="text-white text-lg">♻️</span>
            </div>
            <div>
                <span class="font-bold text-slate-800">Kolektor</span>
                <span class="text-indigo-600 font-bold"> Barang Bekas</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route(auth()->user()->dashboardRoute()) }}"
                   class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                    Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-slate-600 hover:text-indigo-600 text-sm font-medium transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="gradient-hero py-24 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-20 w-96 h-96 bg-purple-300 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-6xl mx-auto px-4 text-center relative">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-sm mb-8 border border-white/20">
            <span>🌱</span> Platform Donasi Barang Bekas Kampus
        </div>
        <h1 class="text-5xl md:text-6xl font-black mb-6 leading-tight">
            Barang Tidak Terpakai?<br>
            <span class="text-yellow-300">Donasikan Sekarang!</span>
        </h1>
        <p class="text-xl text-indigo-100 max-w-2xl mx-auto mb-10 leading-relaxed">
            Platform terpusat untuk mendistribusikan barang bekas layak pakai antar mahasiswa kampus.
            Kurangi limbah, bantu sesama, dan dapatkan poin kontribusi.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="px-8 py-4 bg-yellow-400 text-slate-900 font-bold rounded-2xl hover:bg-yellow-300 transition-colors text-lg shadow-lg">
                Mulai Donasi ♻️
            </a>
            @guest
            <a href="{{ route('login') }}" class="px-8 py-4 bg-white/10 border border-white/30 text-white font-semibold rounded-2xl hover:bg-white/20 transition-colors text-lg">
                Sudah Punya Akun?
            </a>
            @endguest
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-white border-b border-slate-100 py-8">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-3 gap-8 text-center">
        <div>
            <p class="text-3xl font-black text-indigo-600">{{ $stats['total'] }}</p>
            <p class="text-slate-500 text-sm mt-1">Total Donasi</p>
        </div>
        <div>
            <p class="text-3xl font-black text-emerald-600">{{ $stats['completed'] }}</p>
            <p class="text-slate-500 text-sm mt-1">Berhasil Dikirim</p>
        </div>
        <div>
            <p class="text-3xl font-black text-amber-500">{{ $stats['approved'] }}</p>
            <p class="text-slate-500 text-sm mt-1">Tersedia Sekarang</p>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-slate-800">Kategori Donasi</h2>
            <p class="text-slate-500 mt-2">Temukan barang yang kamu butuhkan</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $cat)
            <div class="card-hover bg-white rounded-2xl p-5 text-center border border-slate-100 shadow-sm cursor-pointer">
                <div class="text-4xl mb-3">{{ $cat->icon }}</div>
                <p class="font-semibold text-slate-700 text-sm">{{ $cat->name }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $cat->approved_count ?? 0 }} tersedia</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Donations -->
@if($featuredDonations->isNotEmpty())
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-800">Donasi Terbaru</h2>
                <p class="text-slate-500 mt-1">Barang-barang yang baru tersedia</p>
            </div>
            @auth
            <a href="{{ route('user.catalog.index') }}" class="text-indigo-600 font-semibold text-sm hover:underline">Lihat Semua →</a>
            @endauth
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredDonations as $donation)
            <div class="card-hover bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                @if($donation->firstPhoto())
                    <img src="{{ $donation->firstPhoto() }}" alt="{{ $donation->title }}"
                         class="w-full h-44 object-cover">
                @else
                    <div class="w-full h-44 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                        <span class="text-5xl">{{ $donation->category->icon }}</span>
                    </div>
                @endif
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full font-medium">
                            {{ $donation->category->name }}
                        </span>
                        <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full font-medium">
                            {{ $donation->conditionLabel() }}
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-1 truncate">{{ $donation->title }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2">{{ $donation->description }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img src="{{ $donation->user->avatarUrl() }}" class="w-6 h-6 rounded-full">
                            <span class="text-xs text-slate-500">{{ $donation->user->name }}</span>
                        </div>
                        @auth
                        <a href="{{ route('user.catalog.show', $donation) }}"
                           class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                            Minta →
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                           class="text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg font-semibold hover:bg-slate-200 transition-colors">
                            Login →
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- How It Works -->
<section class="py-20 bg-gradient-to-br from-indigo-50 to-purple-50">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-black text-slate-800 mb-4">Cara Kerja Platform</h2>
        <p class="text-slate-500 mb-12">Proses sederhana dari donasi hingga terkirim</p>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach([
                ['step' => '01', 'emoji' => '📤', 'title' => 'Upload Donasi', 'desc' => 'Donor mengunggah barang dengan foto dan deskripsi lengkap'],
                ['step' => '02', 'emoji' => '✅', 'title' => 'Verifikasi Admin', 'desc' => 'Admin meninjau dan menyetujui barang yang layak donasi'],
                ['step' => '03', 'emoji' => '🙋', 'title' => 'Penerima Meminta', 'desc' => 'Penerima browsing katalog dan mengajukan permintaan'],
                ['step' => '04', 'emoji' => '🚚', 'title' => 'Kurir Mengantarkan', 'desc' => 'Kurir mengambil dan mengirimkan barang ke penerima'],
            ] as $step)
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-600 text-white text-2xl rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    {{ $step['emoji'] }}
                </div>
                <div class="text-xs font-bold text-indigo-400 mb-1">LANGKAH {{ $step['step'] }}</div>
                <h3 class="font-bold text-slate-800 mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-slate-900 text-slate-400 py-10 text-center text-sm">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <span class="text-white">♻️</span>
            </div>
            <span class="text-white font-bold text-lg">Kolektor Barang Bekas</span>
        </div>
        <p>Platform Donasi Barang Kampus — Kurangi Limbah, Bantu Sesama</p>
        <p class="mt-2 text-slate-500">© {{ date('Y') }} Kolektor Barang Bekas. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
