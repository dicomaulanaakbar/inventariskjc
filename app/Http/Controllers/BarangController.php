<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\BarangBeli;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori', 'supplier');

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $barangs = $query->oldest()->paginate(10)->appends($request->all());
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();

        return view('barang.index', compact('barangs', 'kategoris', 'suppliers'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('barang.create', compact('kategoris', 'suppliers'));
    }

    /**
     * Simpan barang baru + Sinkronisasi Riwayat Pembelian
     */
    public function store(Request $request)
    {
        // 1. Validasi dengan tipe numeric agar perhitungan matematika akurat
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'spesifikasi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok'        => 'required|integer|min:0',
            'satuan'      => 'required|string',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // 2. Simpan data ke tabel barangs menggunakan data yang sudah divalidasi
            $barang = Barang::create([
                'nama_barang' => $request->nama_barang,
                'spesifikasi' => $request->spesifikasi,
                'kategori_id' => $request->kategori_id,
                'stok'        => $request->stok,
                'satuan'      => $request->satuan,
                'harga_beli'  => $request->harga_beli,
                'harga_jual'  => $request->harga_jual,
            ]);

            // 3. Catat riwayat stok awal ke tabel barang_belis
            if ($request->stok > 0) {
                // SINKRONISASI: Kita ambil harga langsung dari $barang yang baru disimpan
                // untuk menjamin data di tabel master dan riwayat identik.
                BarangBeli::create([
                    'barang_id'         => $barang->id,
                    'tgl_pembelian'     => now(),
                    'jumlah_barang'     => $barang->stok,
                    'total_bayar'       => ($barang->stok * $barang->harga_beli), // Perhitungan biaya total
                    'status_pembayaran' => 'lunas',
                    'user_id'           => Auth::id(),
                    'keterangan'        => 'Stok awal barang baru'
                ]);
            }

            DB::commit();

            return redirect()->route('barang.index')
                ->with('success', 'Barang dan riwayat stok awal berhasil disinkronkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    // ... method show, edit, update, destroy tetap sama ...
}
