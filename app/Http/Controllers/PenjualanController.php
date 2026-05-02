<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangJual;
use App\Models\BarangJualDetail;
use App\Models\ReturBarang;
use App\Models\BarangBeli;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $penjualans = BarangJual::with('user', 'details.barang')->oldest()->get();

        return view('penjualan.index', compact('penjualans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        return view('penjualan.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'tgl_jual' => 'required|date',
        'metode_pembayaran' => 'required',
        'barang_id' => 'required|array', // Asumsi input barang berupa array
        'jumlah' => 'required|array',
    ]);

    DB::beginTransaction();
    try {
        // 2. HITUNG TOTAL TERLEBIH DAHULU
        // Ini kunci agar tidak error "NOT NULL constraint failed"
        $totalHargaJual = 0;
        foreach ($request->barang_id as $key => $id) {
            $barang = \App\Models\Barang::find($id);
            $totalHargaJual += $barang->harga_jual * $request->jumlah[$key];
        }

        // 3. SIMPAN KE MODEL BarangJual (image_a71607.png)
        $penjualan = \App\Models\BarangJual::create([
            'tgl_jual' => $request->tgl_jual,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga_jual' => $totalHargaJual, // Pastikan variabel ini ada nilainya!
             'user_id' => Auth::id(), 
        ]);

        // 4. SIMPAN KE MODEL BarangJualDetail (image_69894e.png)
        foreach ($request->barang_id as $key => $id) {
            $barang = \App\Models\Barang::find($id);
            $subtotal = $barang->harga_jual * $request->jumlah[$key];

            \App\Models\BarangJualDetail::create([
                'barang_jual_id' => $penjualan->id, // Hubungkan ke ID induk
                'barang_id' => $id,
                'jumlah' => $request->jumlah[$key],
                'harga_satuan' => $barang->harga_jual,
                'subtotal' => $subtotal,
            ]);

            // 5. Kurangi stok barang asli
            $barang->decrement('stok', $request->jumlah[$key]);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
    }
}
    public function show(string $id)
    {
        $penjualan = BarangJual::with('details.barang')->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penjualan = BarangJual::with('details.barang')->findOrFail($id);
        $barangs = Barang::all();
        return view('penjualan.edit', compact('penjualan', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl_jual' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);

        $penjualan = BarangJual::findOrFail($id);

        $penjualan->update([
            'tgl_jual' => $request->tgl_jual,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        $detail = $penjualan->details->first();

         // 🔴 DATA LAMA
    $barangLama = Barang::find($detail->barang_id);
    $jumlahLama = $detail->jumlah;

    // 🟢 DATA BARU
    $barangBaru = Barang::find($request->barang_id);
    $jumlahBaru = $request->jumlah;

    // ✅ 1. KEMBALIKAN STOK LAMA
    $barangLama->stok += $jumlahLama;
    $barangLama->save();

    // ✅ 2. CEK STOK BARU
    if ($barangBaru->stok < $jumlahBaru) {
        return back()->with('error', 'Stok tidak cukup!');
    }

    // ✅ 3. KURANGI STOK BARU
    $barangBaru->stok -= $jumlahBaru;
    $barangBaru->save();

    // ✅ 4. UPDATE PENJUALAN
    $penjualan->update([
        'tgl_jual' => $request->tgl_jual
    ]);

    // ✅ 5. UPDATE DETAIL
    $detail->update([
        'barang_id' => $request->barang_id,
        'jumlah' => $jumlahBaru
    ]);

        $detail->update([
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah
        ]);

        return redirect()->route('penjualan.index')
            ->with('success', 'Penjualan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $penjualan = BarangJual::with('details')->findOrFail($id);

    // ✅ Loop semua detail
    foreach ($penjualan->details as $detail) {

        $barang = Barang::find($detail->barang_id);

        // ✅ KEMBALIKAN STOK
        if ($barang) {
            $barang->stok += $detail->jumlah;
            $barang->save();
        }
    }

    // ✅ Hapus detail setelah stok dikembalikan
    BarangJualDetail::where('barang_jual_id', $id)->delete();

    // ✅ Hapus penjualan
    $penjualan->delete();

    return redirect()->route('penjualan.index')
        ->with('success', 'Penjualan berhasil dihapus & stok kembali');
    }
}
