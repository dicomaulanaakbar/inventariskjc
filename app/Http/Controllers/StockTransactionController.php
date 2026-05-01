<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function masuk(Request $request, Barang $barang)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date'
        ]);

        StockTransaction::create([
            'barang_id' => $barang->id,
            'jenis' => 'masuk',
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal
        ]);

        $barang->increment('stok_saat_ini', $request->jumlah);

        return redirect()->route('barang.stok', $barang)->with('success', 'Stok masuk berhasil');
    }

    public function keluar(Request $request, Barang $barang)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $barang->stok_saat_ini,
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date'
        ]);

        StockTransaction::create([
            'barang_id' => $barang->id,
            'jenis' => 'keluar',
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal
        ]);

        $barang->decrement('stok_saat_ini', $request->jumlah);

        return redirect()->route('barang.stok', $barang)->with('success', 'Stok keluar berhasil');
    }
}