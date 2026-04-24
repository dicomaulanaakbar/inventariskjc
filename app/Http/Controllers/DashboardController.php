<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBeli;
use App\Models\BarangJualDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total barang
        $totalBarang = Barang::count();

        // Stok kurang (misal stok <= 5)
        $stokKurang = Barang::where('stok', '<=', 5)->count();

        // Barang masuk (total jumlah pembelian bulan ini)
        $barangMasuk = BarangBeli::whereMonth('tgl_pembelian', now()->month)
            ->whereYear('tgl_pembelian', now()->year)
            ->sum('jumlah_barang');

        // Barang keluar (total jumlah penjualan bulan ini)
        $barangKeluar = BarangJualDetail::whereHas('barangJual', function ($q) {
            $q->whereMonth('tgl_jual', now()->month)
              ->whereYear('tgl_jual', now()->year);
        })->sum('jumlah');

        // Data untuk tabel (contoh: tampilkan barang dengan stok <= 10 atau semua barang)
        $barangs = Barang::with('kategori')
            ->orderBy('stok', 'asc')
            ->take(10) // batasi 10 baris
            ->get();

        return view('dashboard', compact(
            'totalBarang',
            'stokKurang',
            'barangMasuk',
            'barangKeluar',
            'barangs'
        ));
    }
}
