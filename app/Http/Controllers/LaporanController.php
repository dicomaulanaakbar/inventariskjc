<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBeli;
use App\Models\BarangJual;
use App\Models\BarangJualDetail;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function stok()
    {
        $barangs = Barang::with('kategori')->get();
        return view('laporan.stok', compact('barangs'));
    }

    public function keuangan(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->format('Y-m-d'));
        $end   = $request->input('end', now()->endOfMonth()->format('Y-m-d'));

        $penjualan = BarangJual::with('user')
            ->whereBetween('tgl_jual', [$start, $end])
            ->orderBy('tgl_jual', 'desc')
            ->paginate(15);

        $totalPendapatan = BarangJual::whereBetween('tgl_jual', [$start, $end])->sum('total_harga_jual');

        return view('laporan.keuangan', compact('penjualan', 'totalPendapatan', 'start', 'end'));
    }

    public function barangMasuk(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->format('Y-m-d'));
        $end   = $request->input('end', now()->endOfMonth()->format('Y-m-d'));

        $pembelian = BarangBeli::with(['barang', 'user'])
            ->whereBetween('tgl_pembelian', [$start, $end])
            ->orderBy('tgl_pembelian', 'desc')
            ->paginate(15);

        $totalPembelian = BarangBeli::whereBetween('tgl_pembelian', [$start, $end])->sum('total_biaya');

        return view('laporan.barang-masuk', compact('pembelian', 'totalPembelian', 'start', 'end'));
    }

    public function barangKeluar(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->format('Y-m-d'));
        $end   = $request->input('end', now()->endOfMonth()->format('Y-m-d'));

        $detailPenjualan = BarangJualDetail::with(['barang', 'barangJual'])
            ->whereHas('barangJual', function ($q) use ($start, $end) {
                $q->whereBetween('tgl_jual', [$start, $end]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalTerjual = BarangJualDetail::whereHas('barangJual', function ($q) use ($start, $end) {
            $q->whereBetween('tgl_jual', [$start, $end]);
        })->sum('jumlah');

        return view('laporan.barang-keluar', compact('detailPenjualan', 'totalTerjual', 'start', 'end'));
    }
}