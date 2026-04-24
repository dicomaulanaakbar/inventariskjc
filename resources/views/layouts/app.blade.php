<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- 🔵 SIDEBAR -->
    <aside class="w-64 bg-gray-300 shadow-md">

        <!-- Logo -->
        <div class="p-4 font-bold text-lg border-b">
            KJC STOK
        </div>

        <!-- Menu -->
        <nav class="p-4 space-y-2 text-sm">

            <a href="/dashboard"
               class="flex items-center gap-2 p-2 rounded hover:bg-gray-400
               {{ request()->is('dashboard') ? 'bg-gray-400 font-semibold' : '' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('stok.index') }}"
               class="flex items-center gap-2 p-2 rounded hover:bg-gray-400">
                📦 Stok Barang
            </a>

            <a href="#"
               class="flex items-center gap-2 p-2 rounded hover:bg-gray-400">
                ➕ Tambah Barang
            </a>

            <a href="#"
               class="flex items-center gap-2 p-2 rounded hover:bg-gray-400">
                📥 Barang Masuk
            </a>

            <a href="#"
               class="flex items-center gap-2 p-2 rounded hover:bg-gray-400">
                📤 Barang Keluar
            </a>

            <a href="#"
               class="flex items-center gap-2 p-2 rounded hover:bg-gray-400">
                📄 Laporan
            </a>

        </nav>
    </aside>

    <!-- 🧾 MAIN -->
    <div class="flex-1 flex flex-col">

        <!-- 🔝 NAVBAR -->
        <div class="flex justify-between items-center bg-gray-200 p-4 border-b">

            <h1 class="font-semibold text-lg">
                @yield('title', 'Dashboard')
            </h1>

            <div class="flex items-center gap-4">
                🔔

                <div class="flex items-center gap-2">
                    <span>Admin</span>
                    👤
                </div>
            </div>

        </div>

        <!-- 📄 CONTENT -->
        <div class="p-6">
            @yield('content')
        </div>

    </div>

</div>

</body>
</html>
