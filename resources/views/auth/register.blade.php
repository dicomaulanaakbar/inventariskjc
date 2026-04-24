<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-900 text-white">

<div class="min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- 🔵 Glow -->
    <div class="absolute w-[500px] h-[500px] bg-blue-500 opacity-20 blur-3xl top-[-100px] left-[-100px] rounded-full"></div>
    <div class="absolute w-[400px] h-[400px] bg-indigo-500 opacity-20 blur-3xl bottom-[-100px] right-[-100px] rounded-full"></div>

    <!-- 🖥️ Gambar komputer (background saja) -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <img src="/images/computer.png"
             class="w-[700px] opacity-10 object-contain">
    </div>

    <!-- 🧾 CARD REGISTER (TENGAH) -->
    <div class="relative z-10 w-full max-w-md backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-8">

        <h2 class="text-2xl font-bold text-center mb-1">
            Daftar Akun
        </h2>
        <p class="text-center text-gray-300 text-sm mb-6">
            Buat akun untuk mulai menggunakan sistem
        </p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label class="text-sm text-gray-200">Nama</label>
                <input type="text" name="name" required
                    class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="text-sm text-gray-200">Email</label>
                <input type="email" name="email" required
                    class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="text-sm text-gray-200">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Konfirmasi -->
            <div class="mb-4">
                <label class="text-sm text-gray-200">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                Daftar
            </button>

            <!-- Login -->
            <p class="text-center mt-4 text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-300 font-semibold hover:underline">
                    Login
                </a>
            </p>

        </form>
    </div>

    <!-- Footer -->
    <div class="absolute bottom-4 w-full text-center text-gray-400 text-sm">
        @karim jaya stok 2026 all right reserved
    </div>

</div>

</body>
</html>