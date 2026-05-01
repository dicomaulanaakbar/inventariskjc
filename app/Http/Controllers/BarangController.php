<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;

class BarangController extends Controller
{
     public function index(Request $request)
    {
        $query = Barang::with('kategori', 'supplier');

    // Pencarian berdasarkan nama barang
    if ($request->filled('search')) {
        $query->where('nama_barang', 'like', '%' . $request->search . '%');
    }

    // Filter berdasarkan kategori
    if ($request->filled('kategori_id')) {
        $query->where('kategori_id', $request->kategori_id);
    }

    // Filter berdasarkan supplier
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }

    $barangs = $query->latest()->paginate(10)->appends($request->all());

    // Untuk dropdown filter
    $kategoris = \App\Models\Kategori::all();
    $suppliers = \App\Models\Supplier::all();

    return view('barang.index', compact('barangs', 'kategoris', 'suppliers'));
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
            // 'kode_barang' => 'required|string|max:50|unique:barangs',
            'nama_barang' => 'required|string|max:100',
            'spesifikasi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            // 'supplier_id' => 'nullable|exists:suppliers,id',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer'
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
            'spesifikasi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            // 'supplier_id' => 'nullable|exists:suppliers,id',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
        ]);

        $barang->update($request->only([
            'nama_barang', 'spesifikasi', 'kategori_id','stok', 'satuan', 'harga_beli', 'harga_jual'
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
