<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data barang dengan relasi kategori
        $query = Barang::with('kategori');

        // Logic pencarian sederhana jika dibutuhkan
        if ($request->has('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barangs = $query->get();

        return view('stok.index', compact('barangs'));
    }
}
