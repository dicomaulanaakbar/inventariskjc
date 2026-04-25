<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBeli;
use App\Models\BarangJualDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total barang (distinct, dari tabel barangs)
        $totalBarang = Barang::count();

        // Stok kurang (barang dengan stok <= 5)
        $stokKurang = Barang::where('stok', '<=', 5)->count();

        // Barang masuk bulan ini (total quantity dari pembelian)
        $barangMasuk = BarangBeli::whereMonth('tgl_pembelian', now()->month)
            ->whereYear('tgl_pembelian', now()->year)
            ->sum('jumlah_barang');

        // Barang keluar bulan ini (total quantity dari penjualan)
        $barangKeluar = BarangJualDetail::whereHas('barangJual', function ($q) {
            $q->whereMonth('tgl_jual', now()->month)
              ->whereYear('tgl_jual', now()->year);
        })->sum('jumlah');

        // Data untuk tabel (10 barang dengan stok terendah)
        $barangs = Barang::with('kategori')
            ->orderBy('stok', 'asc')
            ->take(10)
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