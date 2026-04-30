<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturBarang;
use App\Models\ReturDetail;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returs = ReturBarang::with('details.barang')->get();
        return view('retur.index', compact('returs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        return view('retur.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'tgl_return' => 'required|date',
            'alasan_retur' => 'required|string',
            'barang_id' => 'required|exists:barangs,id',
            'status'    => 'required|in:sukses,proses'
        ]);
        DB::beginTransaction();

        try {

            // ❗ CEK: barang sudah pernah diretur atau belum
            $cek = ReturDetail::where('barang_id', $request->barang_id)->first();

            if ($cek) {
                return back()->with('error', 'Barang sudah pernah diretur, tidak boleh sama!');
            }

            // ✅ Simpan ke tabel retur_barang
            $retur = ReturBarang::create([
                'tgl_return' => $request->tgl_return,
                'alasan_retur'    => $request->alasan_retur,
            ]);

            // ✅ Simpan detail (1 barang saja)
            ReturDetail::create([
                'retur_id' => $retur->id,
                'barang_id'=> $request->barang_id,
                'jumlah'   => $request->jumlah,
            ]);

            DB::commit();

            return redirect()->route('retur.index')
                ->with('success', 'Retur berhasil disimpan');

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
        $retur = ReturBarang::with('details.barang')->findOrFail($id);
        return view('retur.show', compact('retur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $retur = ReturBarang::with('details.barang')->findOrFail($id);
        $barangs = Barang::all();
        return view('retur.edit', compact('retur', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     $request->validate([
            'tgl_return' => 'required|date',
            'alasan_retur' => 'required|string',
            'barang_id' => 'required|exists:barangs,id',
            'status'    => 'required|in:sukses,proses',
        ]);

        DB::beginTransaction();

        try {
            $retur = ReturBarang::findOrFail($id);
            $retur->update([
                'tgl_return' => $request->tgl_return,
                'alasan_retur' => $request->alasan_retur,
                'status' => $request->status,
            ]);

            // Update detail (asumsi hanya 1 barang per retur)
            $detail = ReturDetail::where('retur_id', $id)->first();
            if ($detail) {
                $detail->update([
                    'barang_id' => $request->barang_id,
                ]);
            }

            DB::commit();

            return redirect()->route('retur.index')
                ->with('success', 'Retur berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mengupdate: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $retur = ReturBarang::findOrFail($id);

        ReturDetail::where('retur_id', $id)->delete();
        $retur->delete();

        return redirect()->route('retur.index')
            ->with('success', 'Retur berhasil dihapus');
    }

}
