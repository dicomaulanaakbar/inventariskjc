<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KJC Stok - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 5 (opsional, untuk ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom Styles -->
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-boxes me-2"></i>KJC Stok
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'gudang')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                                <i class="fas fa-box me-1"></i> Data Barang
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->role == 'admin')
                       <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}" href="{{ route('supplier.index') }}">
                            <i class="fas fa-truck me-1"></i> Supplier
                        </a>
                    </li>
                      <li class="nav-item">
                         <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                            <i class="fas fa-tags me-1"></i> Kategori
                         </a>
                    </li>
                        @endif

                        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'owner')
                        <li class="nav-item">
                             <a class="nav-link {{ request()->routeIs('catatan.*') ? 'active' : '' }}" href="{{ route('catatan.index') }}">
                            <i class="fa-solid fa-clock-rotate-left"></i> Histori Barang
                         </a>
                        </li>
                        @endif

                        @if (auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('retur.*') ? 'active' : '' }}" href="{{ route('retur.index') }}">
                                <i class="fas fa-undo me-1"></i> Barang Retur
                            </a>
                        </li>
                        @endif

                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'owner')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}" href="{{ route('penjualan.index') }}">
                                <i class="fas fa-shopping-cart me-1"></i> Transaksi
                            </a>
                        </li>  

                        @endif

                        @if(auth()->user()->role == 'owner')
                        <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="laporanDropdown" role="button" data-bs-toggle="dropdown">
        <i class="fas fa-chart-line"></i> Laporan
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('laporan.stok') }}">Laporan Stok</a></li>
        <li><a class="dropdown-item" href="{{ route('laporan.keuangan') }}">Laporan Keuangan</a></li>
        <li><a class="dropdown-item" href="{{ route('laporan.barang-masuk') }}">Barang Masuk</a></li>
        <li><a class="dropdown-item" href="{{ route('laporan.barang-keluar') }}">Barang Keluar</a></li>

        {{-- <li><a class="dropdown-item" href="{{ route('laporan.stok') }}">Laporan Stok</a></li>
        <li><a class="dropdown-item" href="{{ route('laporan.penjualan') }}">Laporan Penjualan</a></li>
        <li><a class="dropdown-item" href="{{ route('laporan.pembelian') }}">Laporan Pembelian</a></li> --}}
    </ul>
</li>
                        @endif
                    @endauth
                </ul>

                <!-- Right side (Notifications + User menu) -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                        @endif
                    @else
                        <!-- Notifikasi Barang Masuk & Keluar -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" title="Notifikasi Barang" data-latest="{{ $latestNotif?->timestamp ?? 0 }}">
                                <i class="fas fa-bell"></i>
                                @if($notifMasuk->isNotEmpty() || $notifKeluar->isNotEmpty())
                                    <span id="notifDot" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle d-none" style="font-size: 0.4rem;">
                                        <span class="visually-hidden">Notifikasi baru</span>
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
                                <li><h6 class="dropdown-header"><i class="fas fa-bell me-1"></i> Notifikasi Barang</h6></li>
                                @if($notifMasuk->isNotEmpty())
                                <li><h6 class="dropdown-header text-success small py-0 mt-1"><i class="fas fa-arrow-down me-1"></i> Barang Masuk</h6></li>
                                @foreach($notifMasuk as $beli)
                                <li>
                                    <a class="dropdown-item small" href="#">
                                        <strong>{{ $beli->barang->nama_barang ?? '-' }}</strong>
                                        <span class="text-success">+{{ $beli->jumlah_barang }} {{ $beli->barang->satuan ?? 'pcs' }}</span>
                                        <br>
                                        <small class="text-muted">{{ $beli->tgl_pembelian->diffForHumans() }}</small>
                                        @if($beli->user)
                                            <small class="text-muted"> — {{ $beli->user->name }}</small>
                                        @endif
                                    </a>
                                </li>
                                @endforeach
                                @endif
                                @if($notifKeluar->isNotEmpty())
                                <li><h6 class="dropdown-header text-warning small py-0 mt-1"><i class="fas fa-arrow-up me-1"></i> Barang Keluar</h6></li>
                                @foreach($notifKeluar as $detail)
                                <li>
                                    <a class="dropdown-item small" href="#">
                                        <strong>{{ $detail->barang->nama_barang ?? '-' }}</strong>
                                        <span class="text-warning">{{ $detail->jumlah }} {{ $detail->barang->satuan ?? 'pcs' }}</span>
                                        <br>
                                        <small class="text-muted">{{ $detail->barangJual?->tgl_jual?->diffForHumans() ?? '-' }}</small>
                                        @if($detail->barangJual?->user)
                                            <small class="text-muted"> — {{ $detail->barangJual->user->name }}</small>
                                        @endif
                                    </a>
                                </li>
                                @endforeach
                                @endif
                                @if($notifMasuk->isEmpty() && $notifKeluar->isEmpty())
                                <li><span class="dropdown-item text-muted small">Belum ada aktivitas barang.</span></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center small text-primary" href="{{ route('catatan.index') }}">Lihat Semua</a></li>
                            </ul>
                        </li>

                        <!-- User Menu -->
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
        <img src="{{ Auth::user()->foto_url }}" width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
        {{ Auth::user()->name }}
        <span class="badge bg-secondary ms-2">{{ ucfirst(Auth::user()->role) }}</span>
    </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-id-card me-2"></i> Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="background: none; border: none;">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-fluid px-4">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="mt-auto py-3 bg-light text-center">
    <div class="container">
        <img src="{{ asset('images/poliwangi.png') }}" alt="Logo" height="30" class="me-2">
        <span class="text-muted">&copy; {{ date('Y') }} Karim Jaya Computer. All rights reserved.</span>
    </div>
</footer>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bell = document.querySelector('.nav-item.dropdown a[data-bs-toggle="dropdown"][title="Notifikasi Barang"]');
            if (!bell) return;
            var dot = document.getElementById('notifDot');
            var latest = parseInt(bell.dataset.latest) || 0;
            var lastRead = parseInt(localStorage.getItem('notifLastRead')) || 0;

            if (latest > lastRead && dot) {
                dot.classList.remove('d-none');
            }

            bell.closest('.dropdown').addEventListener('show.bs.dropdown', function () {
                if (dot) dot.classList.add('d-none');
                if (latest > 0) localStorage.setItem('notifLastRead', latest);
            });
        });
    </script>
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>
