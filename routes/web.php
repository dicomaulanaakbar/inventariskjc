<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CatatanBarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Tambahan route untuk update foto dan data tambahan
    Route::patch('/profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.update.profile');
    
    // Route untuk update password
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    
    // Route untuk hapus akun
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //kategori
    Route::resource('kategori', KategoriController::class);

    // CRUD Barang (admin)
    Route::resource('barang', BarangController::class);

    //supplier
    Route::resource('supplier', SupplierController::class);


    // // Transaksi stok

    // Route::get('/barang/{barang}/stok-masuk', [TransaksiController::class, 'formStokMasuk'])->name('stok.masuk.form');
    // Route::post('/barang/{barang}/stok-masuk', [TransaksiController::class, 'stokMasuk'])->name('stok.masuk');
    // Route::get('/barang/{barang}/stok-keluar', [TransaksiController::class, 'formStokKeluar'])->name('stok.keluar.form');
    // Route::post('/barang/{barang}/stok-keluar', [TransaksiController::class, 'stokKeluar'])->name('stok.keluar');
    // Route::get('/barang/{barang}/riwayat', [TransaksiController::class, 'riwayat'])->name('barang.riwayat');

    Route::get('/catatan', [CatatanBarangController::class, 'index'])->name('catatan.index');
    Route::get('/catatan/stok-masuk/{barang?}', [CatatanBarangController::class, 'formStokMasuk'])->name('stok.masuk.form');
    Route::post('/catatan/stok-masuk/{barang}', [CatatanBarangController::class, 'stokMasuk'])->name('stok.masuk');
    Route::get('/catatan/stok-keluar/{barang?}', [CatatanBarangController::class, 'formStokKeluar'])->name('stok.keluar.form');
    Route::post('/catatan/stok-keluar', [CatatanBarangController::class, 'stokKeluar'])->name('stok.keluar');

   // Laporan
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/laporan/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('laporan.barang-masuk');
    Route::get('/laporan/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('laporan.barang-keluar');

    // //catatan
    // Route::get('/catatan/stok-masuk/{barang}', [CatatanBarangController::class, 'formStokMasuk'])->name('stok.masuk.form');
    // Route::post('/catatan/stok-masuk/{barang}', [CatatanBarangController::class, 'stokMasuk'])->name('stok.masuk');

    // Route::get('/catatan/stok-keluar/{barang?}', [CatatanBarangController::class, 'formStokKeluar'])->name('stok.keluar.form');
    // Route::post('/catatan/stok-keluar', [CatatanBarangController::class, 'stokKeluar'])->name('stok.keluar');
});

    //kategori
    Route::resource('kategori', KategoriController::class);

require __DIR__.'/auth.php';