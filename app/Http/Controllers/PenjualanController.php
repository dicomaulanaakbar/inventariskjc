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
    $request->validate([
        'tgl_jual' => 'required|date',
        'metode_pembayaran' => 'required|in:qris,tunai,transfer',
        'barang_id' => 'required|exists:barangs,id',
        'jumlah' => 'required|integer|min:1',
]);

   DB::beginTransaction();

try {
    $barang = Barang::findOrFail($request->barang_id);

    if ($barang->stok < $request->jumlah) {
        return back()->with('error', 'Stok tidak cukup');
    }

    $total = $barang->harga_jual * $request->jumlah;

    // HEADER
    $penjualan = BarangJual::create([
        'tgl_jual' => $request->tgl_jual,
        'metode_pembayaran' => $request->metode_pembayaran,
        'user_id' => Auth::id(),
        'total_harga_jual' => $total,
        'barang_id' => $request->barang_id,
        'jumlah' => $request->jumlah,
        
    ]);

    // DETAIL
    BarangJualDetail::create([
        'barang_jual_id' => $penjualan->id,
        'barang_id' => $request->barang_id,
        'jumlah' => $request->jumlah,
        'harga_satuan' => $barang->harga_jual,
        'subtotal' => $total,
    ]);

    // KURANGI STOK
    $barang->decrement('stok', $request->jumlah);

    DB::commit();

    return redirect()->route('penjualan.index')
                         ->with('success', 'Berhasil disimpan');


} catch (\Exception $e) {
    DB::rollback();
    return back()->with('error', $e->getMessage());
}
}
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

    $barangLama = Barang::find($detail->barang_id);
    $jumlahLama = $detail->jumlah;

    $barangBaru = Barang::find($request->barang_id);
    $jumlahBaru = $request->jumlah;

    $barangLama->stok += $jumlahLama;
    $barangLama->save();

    if ($barangBaru->stok < $jumlahBaru) {
        return back()->with('error', 'Stok tidak cukup!');
    }

    $barangBaru->stok -= $jumlahBaru;
    $barangBaru->save();

    $penjualan->update([
        'tgl_jual' => $request->tgl_jual
    ]);

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

    foreach ($penjualan->details as $detail) {

        $barang = Barang::find($detail->barang_id);

        if ($barang) {
            $barang->stok += $detail->jumlah;
            $barang->save();
        }
    }

    BarangJualDetail::where('barang_jual_id', $id)->delete();

    $penjualan->delete();

    return redirect()->route('penjualan.index')
        ->with('success', 'Penjualan berhasil dihapus & stok kembali');
    }

     public function show($id)
    {
        $penjualan = BarangJual::with('user', 'details.barang')->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }
}
