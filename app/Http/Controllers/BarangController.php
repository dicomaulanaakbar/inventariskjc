<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;

class BarangController extends Controller
{
     public function index()
    {
        $barangs = Barang::with(['kategori', 'supplier'])->latest()->paginate(10);
        return view('barang.index', compact('barangs'));
    }

    /**
     * Form tambah barang
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('barang.create', compact('kategoris', 'suppliers'));
    }

    /**
     * Simpan barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:50|unique:barangs',
            'nama_barang' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategoris,id',
            'supplier_id' => 'nullable|exists:suppliers,id'
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Detail barang (biasanya tidak dipakai, bisa diarahkan ke edit atau stok)
     */
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Form edit barang
     */
    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('barang.edit', compact('barang', 'kategoris', 'suppliers'));
    }

    /**
     * Update barang
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategoris,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
        ]);

        $barang->update($request->only([
            'nama_barang', 'kategori_id', 'supplier_id', 'satuan', 'harga_beli', 'harga_jual'
        ]));

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diupdate.');
    }

    /**
     * Hapus barang
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
