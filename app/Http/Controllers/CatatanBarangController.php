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
    // ==================== STOK MASUK ====================
    public function formStokMasuk($barangId = null)
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $barang = null;

        if ($barangId) {
            $barang = Barang::find($barangId);
            if (!$barang) {
                return redirect()->route('barang.index')->with('error', 'Barang tidak ditemukan.');
            }
        }

        return view('catatan.stok-masuk', compact('barangs', 'barang'));
    }

    public function stokMasuk(Request $request, $barangId = null)
    {
        // Validasi input
        $rules = [
            'jumlah' => 'required|integer|min:1',
            'harga_beli_satuan' => 'required|integer|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        // Jika tidak ada parameter barangId, harus pilih barang dari dropdown
        if (!$barangId) {
            $rules['barang_id'] = 'required|exists:barangs,id';
        }

        $request->validate($rules);

        // Tentukan barang yang akan diproses
        $barang = $barangId ? Barang::find($barangId) : Barang::find($request->barang_id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $total_bayar = $request->jumlah * $request->harga_beli_satuan;

            BarangBeli::create([
                'barang_id' => $barang->id,
                'tgl_pembelian' => $request->tanggal,
                'jumlah_barang' => $request->jumlah,
                'total_bayar' => $total_bayar,
                'status_pembayaran' => 'lunas',
                'user_id' => Auth::id(),
                'keterangan' => $request->keterangan,
            ]);

            // Update stok barang
            $barang->increment('stok', $request->jumlah);
            // Update harga beli terakhir (optional)
            $barang->harga_beli = $request->harga_beli_satuan;
            $barang->save();

            DB::commit();
            return redirect()->route('barang.index')->with('success', 'Stok masuk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    // ==================== STOK KELUAR ====================
    public function formStokKeluar($barangId = null)
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $barang = null;

        if ($barangId) {
            $barang = Barang::find($barangId);
            if (!$barang) {
                return redirect()->route('barang.index')->with('error', 'Barang tidak ditemukan.');
            }
        }

        return view('catatan.stok-keluar', compact('barangs', 'barang'));
    }

    public function stokKeluar(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_jual_satuan' => 'required|integer|min:0',
            'metode_pembayaran' => 'required|in:tunai,transfer,qris',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::find($request->barang_id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        if ($barang->stok < $request->jumlah) {
            return redirect()->back()->with('error', "Stok tidak mencukupi. Stok tersedia: {$barang->stok}")->withInput();
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
            return redirect()->route('barang.index')->with('success', 'Stok keluar (penjualan) berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }
}