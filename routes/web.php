<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung ke dashboard (login dulu jika pakai auth)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Barang (admin & gudang)
    Route::resource('barang', BarangController::class);

    // // Transaksi stok
    // Route::get('/barang/{barang}/stok-masuk', [TransaksiController::class, 'formStokMasuk'])->name('stok.masuk.form');
    // Route::post('/barang/{barang}/stok-masuk', [TransaksiController::class, 'stokMasuk'])->name('stok.masuk');
    // Route::get('/barang/{barang}/stok-keluar', [TransaksiController::class, 'formStokKeluar'])->name('stok.keluar.form');
    // Route::post('/barang/{barang}/stok-keluar', [TransaksiController::class, 'stokKeluar'])->name('stok.keluar');
    // Route::get('/barang/{barang}/riwayat', [TransaksiController::class, 'riwayat'])->name('barang.riwayat');

    // // Laporan
    // Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    // Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    // Route::get('/laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
});

require __DIR__.'/auth.php';