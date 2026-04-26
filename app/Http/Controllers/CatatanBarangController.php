<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangBeli;
use App\Models\BarangJual;
use App\Models\BarangJualDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CatatanBarangController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->format('Y-m-d'));
        $end   = $request->input('end', now()->endOfMonth()->format('Y-m-d'));

         $pembelian = BarangBeli::with(['barang', 'user'])
            ->whereBetween('tgl_pembelian', [$start, $end])
            ->orderBy('tgl_pembelian', 'desc')
            ->get();

         $penjualan = BarangJual::with(['user', 'details.barang'])
            ->whereBetween('tgl_jual', [$start, $end])
            ->orderBy('tgl_jual', 'desc')
            ->get();

          $totalPendapatan = BarangJual::whereBetween('tgl_jual', [$start, $end])->sum('total_harga_jual');

       
        $totalPengeluaran = BarangBeli::whereBetween('tgl_pembelian', [$start, $end])->sum('total_biaya');

        
        $labaKotor = $totalPendapatan - $totalPengeluaran;

        return view('catatan.index', compact(
            'pembelian', 'penjualan', 'start', 'end',
            'totalPendapatan', 'totalPengeluaran', 'labaKotor'
        ));
    }

     public function formStokMasuk(Barang $barang)
    {
        return view('catatan.stok-masuk', compact('barang'));
    }

      public function stokMasuk(Request $request, Barang $barang)
    {
         $request->validate([
            'jumlah' => 'required|integer|min:1',
            'harga_beli_satuan' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $total_biaya = $request->jumlah * $request->harga_beli_satuan;
            BarangBeli::create([
                'barang_id' => $barang->id,
                'tgl_pembelian' => $request->tanggal,
                'jumlah_barang' => $request->jumlah,
                'total_biaya' => $total_biaya,
                'status_pembayaran' => 'lunas',
                'user_id' => Auth::id(),  
            ]);
            $barang->increment('stok', $request->jumlah);
            $barang->update(['harga_beli' => $request->harga_beli_satuan]);
            DB::commit();
            return redirect()->route('catatan.index')->with('success', 'Stok masuk berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function formStokKeluar(Request $request, ?Barang $barang = null)
    {
        if ($barang) {
            return view('catatan.stok-keluar', compact('barang'));
        }

        $barangs = Barang::orderBy('nama_barang')->get();
        return view('catatan.stok-keluar', compact('barangs'));
    }

     public function stokKeluar(Request $request)
    {
         $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_jual_satuan' => 'required|integer|min:0',
            'metode_pembayaran' => 'required|in:tunai,transfer,qris,cicil',
            'tanggal' => 'required|date',
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        DB::beginTransaction();
        try {
            $subtotal = $request->jumlah * $request->harga_jual_satuan;
            $penjualan = BarangJual::create([
                'tgl_jual' => $request->tanggal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_harga_jual' => $subtotal,
                'user_id' => Auth::id(),  
            ]);
            BarangJualDetail::create([
                'barang_jual_id' => $penjualan->id,
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_jual_satuan,
                'subtotal' => $subtotal,
            ]);
            $barang->decrement('stok', $request->jumlah);
            DB::commit();
            return redirect()->route('catatan.index')->with('success', 'Penjualan berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
