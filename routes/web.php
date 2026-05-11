<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CatatanBarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD & AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.update.profile');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // Kategori, Supplier, Barang
        Route::resource('kategori', KategoriController::class);
        Route::resource('supplier', SupplierController::class);
        Route::resource('barang', BarangController::class);
        Route::resource('penjualan', PenjualanController::class);

        /* --- FITUR RETUR --- */
        // PENTING: Route detail diletakkan sebelum resource agar tidak bentrok dengan {retur} ID
        Route::get('/retur/get-details/{id}', [ReturController::class, 'getPenjualanDetails'])
            ->name('retur.get-details');

        Route::patch('/retur/{id}/status', [ReturController::class, 'updateStatus'])
            ->name('retur.updateStatus');

        Route::resource('retur', ReturController::class);
        /* -------------------- */

        // Catatan Barang & Stok
        Route::resource('catatan', CatatanBarangController::class);
        Route::get('/catatan/stok-masuk/{barang?}', [CatatanBarangController::class, 'formStokMasuk'])->name('stok.masuk.form');
        Route::post('/catatan/stok-masuk/{barang}', [CatatanBarangController::class, 'stokMasuk'])->name('stok.masuk');
        Route::get('/catatan/stok-keluar/{barang?}', [CatatanBarangController::class, 'formStokKeluar'])->name('stok.keluar.form');
        Route::post('/catatan/stok-keluar', [CatatanBarangController::class, 'stokKeluar'])->name('stok.keluar');
    });

    /*
    |--------------------------------------------------------------------------
    | OWNER ONLY
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
        Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
        Route::get('/laporan/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('laporan.barang-masuk');
        Route::get('/laporan/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('laporan.barang-keluar');
        Route::get('/laporan/keuangan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.keuangan.pdf');
        Route::get('/laporan/keuangan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.keuangan.excel');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN + OWNER
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,owner'])->group(function () {
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::get('/catatan', [CatatanBarangController::class, 'index'])->name('catatan.index');
    });

});

require __DIR__ . '/auth.php';
