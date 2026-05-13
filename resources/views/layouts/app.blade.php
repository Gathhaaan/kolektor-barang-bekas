<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kolektor Barang Bekas - Platform donasi barang kampus yang terorganisir">
    <title>@yield('title', 'Dashboard') — Kolektor Barang Bekas</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Chart.js (for reports) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200; }
        .sidebar-link.active { @apply bg-brand-600 text-white shadow-md; }
        .sidebar-link:not(.active) { @apply text-slate-600 hover:bg-slate-100 hover:text-brand-700; }
        .stat-card { @apply bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow; }
        .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-slate-50 font-sans antialiased">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col shadow-sm flex-shrink-0 overflow-y-auto">
        <!-- Logo -->
        <div class="px-6 py-5 border-b border-slate-100">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-brand-600 to-brand-800 rounded-xl flex items-center justify-center shadow-md">
                    <span class="text-white text-lg">♻️</span>
                </div>
                <div>
                    <p class="font-bold text-slate-800 text-sm leading-tight">Kolektor</p>
                    <p class="text-xs text-slate-400">Barang Bekas</p>
                </div>
            </a>
        </div>

        <!-- User Info -->
        <div class="px-4 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="Avatar"
                     class="w-10 h-10 rounded-full object-cover ring-2 ring-brand-100">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <span class="text-xs px-2 py-0.5 bg-brand-50 text-brand-700 rounded-full font-medium">
                        {{ auth()->user()->roleName() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1">
            @yield('sidebar-nav')
        </nav>

        <!-- Sidebar Footer -->
        <div class="px-3 py-4 border-t border-slate-100 space-y-1">
            <a href="{{ route('home') }}" class="sidebar-link">
                🏠 <span>Halaman Utama</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-500 hover:bg-red-50 hover:text-red-700">
                    🚪 <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-slate-400">@yield('page-subtitle', '')</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Notifications -->
                @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open"
                            class="relative p-2 rounded-xl hover:bg-slate-100 transition-colors">
                        <span class="text-xl">🔔</span>
                        @if($unreadCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" x-cloak
                         class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                            <span class="font-semibold text-slate-800 text-sm">Notifikasi</span>
                            @if($unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button class="text-xs text-brand-600 hover:underline">Tandai semua dibaca</button>
                            </form>
                            @endif
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            @forelse(auth()->user()->appNotifications()->take(8)->get() as $notif)
                            <div class="px-4 py-3 border-b border-slate-50 {{ $notif->isUnread() ? 'bg-brand-50' : '' }} hover:bg-slate-50 transition-colors">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800">{{ $notif->title }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if($notif->isUnread())
                                    <form method="POST" action="{{ route('notifications.read', $notif) }}">
                                        @csrf
                                        <button class="flex-shrink-0 w-2 h-2 bg-brand-500 rounded-full mt-1.5 hover:bg-brand-700 transition-colors"></button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="px-4 py-8 text-center text-slate-400 text-sm">
                                Tidak ada notifikasi
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <img src="{{ auth()->user()->avatarUrl() }}" alt="Profile"
                     class="w-9 h-9 rounded-full ring-2 ring-brand-200 cursor-pointer">
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm mb-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm mb-2">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm mb-2">
                    <p class="font-semibold mb-1">⚠️ Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto px-6 py-4">
            @yield('content')
        </main>
    </div>
</div>

<!-- Alpine.js for dropdowns -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@stack('scripts')
</body>
</html>
