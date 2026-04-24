<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBeli;
use App\Models\BarangJual;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Laporan stok barang
     */
    public function stok()
    {
        $barangs = Barang::with('kategori')->orderBy('stok', 'asc')->get();
        return view('laporan.stok', compact('barangs'));
    }

    /**
     * Laporan penjualan per periode
     */
    public function penjualan(Request $request)
    {
        $start = $request->get('start', now()->startOfMonth());
        $end = $request->get('end', now()->endOfMonth());

        $penjualan = BarangJual::with('user', 'details.barang')
            ->whereBetween('tgl_jual', [$start, $end])
            ->latest()
            ->paginate(20);

        $total = $penjualan->sum('total_harga_jual');

        return view('laporan.penjualan', compact('penjualan', 'total', 'start', 'end'));
    }

    /**
     * Laporan pembelian per periode
     */
    public function pembelian(Request $request)
    {
        $start = $request->get('start', now()->startOfMonth());
        $end = $request->get('end', now()->endOfMonth());

        $pembelian = BarangBeli::with('user', 'barang')
            ->whereBetween('tgl_pembelian', [$start, $end])
            ->latest()
            ->paginate(20);

        $total = $pembelian->sum('total_biaya');

        return view('laporan.pembelian', compact('pembelian', 'total', 'start', 'end'));
    }
}