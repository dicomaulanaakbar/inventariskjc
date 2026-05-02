<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBeli;
use App\Models\BarangJual;
use App\Models\BarangJualDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CatatanBarangController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN UTAMA CATATAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        // 1. Mengambil data transaksi utama
        $barangMasuk = BarangBeli::with('barang')->latest()->get();

        // Alias $pembelian untuk memperbaiki error baris 99 (image_0459d7.png)
        $pembelian = $barangMasuk;

        // Mengambil data penjualan (barang keluar)
        $barangKeluar = BarangJual::with('details.barang')->latest()->get();

        // Alias $penjualan untuk memperbaiki error baris 70 (image_0459d7.png)
        $penjualan = $barangKeluar;

        // 2. Menghitung Ringkasan Keuangan
        $totalPendapatan = BarangJual::sum('total_harga_jual');
        $totalPengeluaran = BarangBeli::sum('total_bayar');

        // Fix error image_045d02.png
        $labaKotor = $totalPendapatan - $totalPengeluaran;

        // 3. Variabel pendukung navigasi/tabel
        // Fix error image_04b46c.png & image_04615b.png
        $start = 1;
        $end = 10;

        // 4. Kirim SEMUA variabel ke view
        return view('catatan.index', compact(
            'barangMasuk',
            'pembelian',
            'barangKeluar',
            'penjualan',
            'totalPendapatan',
            'totalPengeluaran',
            'labaKotor',
            'start',
            'end'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM & PROSES STOK MASUK
    |--------------------------------------------------------------------------
    */

    public function formStokMasuk($barangId = null)
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $barang = $barangId ? Barang::find($barangId) : null;

        if ($barangId && !$barang) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak ditemukan.');
        }

        return view('catatan.stok-masuk', compact('barangs', 'barang'));
    }

    public function stokMasuk(Request $request, $barangId = null)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'harga_beli_satuan' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'barang_id' => $barangId ? 'nullable' : 'required|exists:barangs,id',
        ]);

        $barang = $barangId ? Barang::find($barangId) : Barang::find($request->barang_id);

        DB::beginTransaction();
        try {
            BarangBeli::create([
                'barang_id' => $barang->id,
                'tgl_pembelian' => $request->tanggal,
                'jumlah_barang' => $request->jumlah,
                'total_bayar' => $request->jumlah * $request->harga_beli_satuan,
                'status_pembayaran' => 'lunas',
                'user_id' => Auth::id(),
                'keterangan' => $request->keterangan,
            ]);

            $barang->increment('stok', $request->jumlah);
            $barang->update(['harga_beli' => $request->harga_beli_satuan]);

            DB::commit();
            return redirect()->route('catatan.index')->with('success', 'Stok masuk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FORM & PROSES STOK KELUAR
    |--------------------------------------------------------------------------
    */

    public function formStokKeluar($barangId = null)
    {
        $barangs = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        $barang = $barangId ? Barang::find($barangId) : null;

        return view('catatan.stok-keluar', compact('barangs', 'barang'));
    }

    public function stokKeluar(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_jual_satuan' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:tunai,transfer,qris',
            'tanggal' => 'required|date',
        ]);

        $barang = Barang::find($request->barang_id);

        if ($barang->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak cukup. Sisa: ' . $barang->stok)->withInput();
        }

        DB::beginTransaction();
        try {
            $subtotal = $request->jumlah * $request->harga_jual_satuan;

            $penjualan_data = BarangJual::create([
                'tgl_jual' => $request->tanggal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_harga_jual' => $subtotal,
                'user_id' => Auth::id(),
            ]);

            BarangJualDetail::create([
                'barang_jual_id' => $penjualan_data->id,
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_jual_satuan,
                'subtotal' => $subtotal,
            ]);

            $barang->decrement('stok', $request->jumlah);

            DB::commit();
            return redirect()->route('catatan.index')->with('success', 'Stok keluar berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }
}
