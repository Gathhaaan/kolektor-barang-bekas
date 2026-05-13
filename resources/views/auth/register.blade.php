<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Kolektor Barang Bekas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter','sans-serif'] } } } }</script>
</head>
<body class="font-sans antialiased min-h-screen bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 px-8 py-8 text-white text-center">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl">📝</span>
            </div>
            <h1 class="text-2xl font-black">Buat Akun Baru</h1>
            <p class="text-indigo-200 text-sm mt-1">Bergabung dengan komunitas donasi kampus</p>
        </div>

        <div class="px-8 py-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Saya ingin bergabung sebagai</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($roles as $role)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role_id" value="{{ $role->id }}"
                                   class="peer sr-only" {{ old('role_id') == $role->id ? 'checked' : '' }}
                                   {{ $loop->first ? 'checked' : '' }}>
                            <div class="p-3 border-2 border-slate-200 rounded-xl text-center transition-all
                                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50">
                                <p class="font-semibold text-sm text-slate-700">
                                    {{ $role->name === 'donor' ? '📤 Pendonasi' : '🙋 Penerima' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $role->name === 'donor' ? 'Upload barang donasi' : 'Minta barang donasi' }}
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('role_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none text-sm @error('name') border-red-400 @enderror"
                           placeholder="Nama lengkap kamu">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none text-sm @error('email') border-red-400 @enderror"
                           placeholder="email@kampus.ac.id">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP <span class="text-slate-400 font-normal">(opsional)</span></label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none text-sm"
                           placeholder="08xxxxxxxxxx">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none text-sm"
                           placeholder="Minimal 8 karakter">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none text-sm"
                           placeholder="Ulangi kata sandi">
                </div>

                <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors shadow-sm">
                    Buat Akun →
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
    <p class="text-center text-indigo-200 text-xs mt-4">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">← Kembali ke halaman utama</a>
    </p>
</div>
</body>
</html>
