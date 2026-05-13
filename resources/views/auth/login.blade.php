<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Kolektor Barang Bekas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter','sans-serif'] } } } }</script>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <!-- Card -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 px-8 py-8 text-white text-center">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <span class="text-3xl">♻️</span>
            </div>
            <h1 class="text-2xl font-black">Selamat Datang!</h1>
            <p class="text-indigo-200 text-sm mt-1">Masuk ke akun Kolektor Barang Bekas</p>
        </div>

        <div class="px-8 py-8">
            <!-- Session Error -->
            @if(session('status'))
                <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-sm @error('email') border-red-400 @enderror"
                           placeholder="email@kampus.ac.id">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Kata Sandi</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition text-sm"
                           placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600">
                        Ingat saya
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Lupa kata sandi?</a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors shadow-sm">
                    Masuk →
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">Daftar di sini</a>
            </p>

            <!-- Demo accounts -->
            <div class="mt-6 p-4 bg-slate-50 rounded-xl text-xs text-slate-500">
                <p class="font-semibold text-slate-700 mb-2">🔑 Akun Demo:</p>
                <p>Admin: admin@kolektor.ac.id</p>
                <p>Donor: donor@kolektor.ac.id</p>
                <p>Penerima: penerima@kolektor.ac.id</p>
                <p>Kurir: kurir@kolektor.ac.id</p>
                <p class="mt-1 text-slate-400">Password: password</p>
            </div>
        </div>
    </div>

    <p class="text-center text-indigo-200 text-xs mt-4">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">← Kembali ke halaman utama</a>
    </p>
</div>
</body>
</html>
