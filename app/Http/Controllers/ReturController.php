<?php

namespace App\Http\Controllers;

use App\Models\ReturBarang;
use App\Models\ReturDetail;
use App\Models\BarangJual;
use App\Models\BarangJualDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturController extends Controller
{
    /**
     * Menampilkan daftar retur barang.
     */
    public function index(Request $request)
    {
        $returns = ReturBarang::with('barangJual', 'details.barang')
            ->orderBy('tgl_return', 'desc')
            ->paginate(15);

        return view('retur.index', compact('returns'));
    }

    /**
     * Menampilkan form tambah retur.
     * FITUR TAMBAHAN: Mengambil data minimal penjualan untuk dropdown.
     */
    public function create(Request $request)
    {
        // Mengambil ID dan Tanggal saja agar performa lebih ringan
        $penjualan = BarangJual::select('id', 'tgl_jual')
            ->orderBy('tgl_jual', 'desc')
            ->get();

        $barang = Barang::all();

        return view('retur.create', compact('penjualan', 'barang'));
    }

    /**
     * Menyimpan data retur dan mengupdate stok barang secara otomatis.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_jual_id' => 'required|exists:barang_juals,id',
            'tgl_return'     => 'required|date',
            'alasan_retur'   => 'required|string|max:50',
            'keterangan'     => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah'    => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = BarangJual::findOrFail($request->barang_jual_id);

            $return = ReturBarang::create([
                'tgl_return'     => $request->tgl_return,
                'alasan_retur'   => $request->alasan_retur,
                'barang_jual_id' => $penjualan->id,
                'keterangan'     => $request->keterangan,
                'status_retur'   => 'proses',
            ]);

            foreach ($request->items as $item) {
                // Validasi: Apakah barang ada di transaksi asli?
                $detailAsli = BarangJualDetail::where('barang_jual_id', $penjualan->id)
                    ->where('barang_id', $item['barang_id'])
                    ->first();

                if (!$detailAsli || $item['jumlah'] > $detailAsli->jumlah) {
                    throw new \Exception('Jumlah retur barang ' . ($detailAsli->barang->nama_barang ?? '') . ' melebihi jumlah terjual.');
                }

                ReturDetail::create([
                    'return_id' => $return->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah'    => $item['jumlah'],
                ]);

                // Update stok (tambah kembali)
                $barang = Barang::findOrFail($item['barang_id']);
                $barang->increment('stok', $item['jumlah']);
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan retur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * API untuk mengambil detail barang dari sebuah transaksi penjualan.
     * DIGUNAKAN OLEH: JavaScript di halaman create.
     */
    public function getPenjualanDetails($id)
    {
        $details = BarangJualDetail::with('barang')
            ->where('barang_jual_id', $id)
            ->get();

        return response()->json($details);
    }

    public function show(ReturBarang $retur)
    {
        $retur->load('barangJual', 'details.barang');
        return view('retur.show', compact('retur'));
    }

    public function edit(ReturBarang $retur)
    {
        return view('retur.edit', compact('retur'));
    }

    public function update(Request $request, ReturBarang $retur)
    {
        $request->validate([
            'tgl_return'   => 'required|date',
            'status_retur' => 'required|in:sukses,proses,batal'
        ]);

        $retur->update([
            'tgl_return'   => $request->tgl_return,
            'status_retur' => $request->status_retur
        ]);

        return redirect()->route('retur.show', $retur)->with('success', 'Data diperbarui.');
    }

    public function destroy(ReturBarang $return)
    {
        DB::beginTransaction();
        try {
            foreach ($return->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->decrement('stok', $detail->jumlah);
                }
            }
            $return->details()->delete();
            $return->delete();

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hapus.');
        }
    }
}
