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
         $penjualans = BarangJual::with('user', 'details.barang')->latest()->get();

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
            'metode_pembayaran' => 'required|string',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // simpan header
            $penjualan = BarangJual::create([
                'tgl_jual' => $request->tgl_jual,
                'metode_pembayaran' => $request->metode_pembayaran,
                'user_id' => Auth::id(),
            ]);

            // simpan detail
            BarangJualDetail::create([
                'barang_jual_id' => $penjualan->id,
                'barang_id' => $request->barang_id,
                'jumlah' => $request->jumlah,
            ]);

                $barang = Barang::find($request->barang_id);
                $barang->decrement('stok', $request->jumlah);
                $barang->save();

            DB::commit();

            return redirect()->route('penjualan.index')
                ->with('success', 'Penjualan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
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

        return redirect()->route('penjualan.index')
            ->with('success', 'Penjualan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penjualan = BarangJual::findOrFail($id);

        // hapus detail dulu
        BarangJualDetail::where('barang_jual_id', $id)->delete();

        $penjualan->delete();

        return redirect()->route('penjualan.index')
            ->with('success', 'Penjualan berhasil dihapus');
    }
}
