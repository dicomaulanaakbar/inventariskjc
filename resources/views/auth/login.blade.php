<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-300">

    <div class="min-h-screen flex bg-slate-900 text-white relative overflow-hidden">

    
    <!-- 🔵 Background glow -->
    <div class="absolute w-[500px] h-[500px] bg-blue-500 opacity-20 blur-3xl top-[-100px] left-[-100px] rounded-full"></div>
    <div class="absolute w-[400px] h-[400px] bg-indigo-500 opacity-20 blur-3xl bottom-[-100px] right-[-100px] rounded-full"></div>

    <!-- 🧾 LOGIN AREA -->
    <div class="w-full lg:w-1/2 flex items-center justify-center z-10 px-6">

        <!-- CARD -->
        <div class="w-full max-w-md backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl shadow-2xl p-8">

            <h2 class="text-2xl font-bold text-center mb-1 text-white">
                LOGIN
            </h2>
            <p class="text-center text-gray-300 text-sm mb-6">
                Sistem Inventaris Karim Jaya Computer
            </p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="text-sm text-gray-200">Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="text-sm text-gray-200">Password</label>
                    <input type="password" name="password"
                        class="w-full mt-1 px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <!-- Remember -->
                <div class="flex justify-between items-center text-sm mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" class="mr-2"> Remember me
                    </label>

                    <a href="#" class="text-blue-300 hover:underline">
                        Lupa password?
                    </a>
                </div>

                <!-- Button -->
                <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                    Login
                </button>

                <!-- Register -->
                <p class="text-center mt-4 text-sm">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-300 font-semibold hover:underline">
                        Daftar
                    </a>
                </p>

            </form>
        </div>
    </div>

    <!-- 💻 RIGHT: BRANDING -->
    <div class="hidden lg:flex w-1/2 items-center justify-center p-10 relative z-10">

        <div class="max-w-md text-left">

            <!-- Logo nanti di sini -->
            <!-- <img src="/logo.png" class="mb-6 w-24"> -->

            <h1 class="text-4xl font-bold leading-tight mb-4">
                KARIM JAYA COMPUTER
            </h1>

            <p class="text-gray-300 text-lg mb-2">
                Sistem Inventaris
            </p>

            <p class="text-gray-400 text-sm">
                Kelola stok barang, transaksi masuk & keluar, 
                serta laporan inventaris dengan mudah dan efisien.
            </p>

        </div>
    </div>

    <!-- Footer -->
    <div class="absolute bottom-4 w-full text-center text-gray-400 text-sm">
        @karim jaya stok 2026 all right reserved
    </div>

</div>

    <!-- Footer -->
    <div class="absolute bottom-4 w-full text-center text-gray-400 text-sm">
        @karim jaya stok 2026 all right reserved
    </div>

</div>

</body>
</html>