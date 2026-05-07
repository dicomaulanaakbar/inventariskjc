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
     */
    public function create(Request $request)
    {
        // Mengambil data penjualan yang tersedia untuk diretur
        $penjualan = BarangJual::with('details.barang')
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
            // 1. Ambil data penjualan asli
            $penjualan = BarangJual::findOrFail($request->barang_jual_id);

            // 2. Buat Header Retur
            $return = ReturBarang::create([
                'tgl_return'     => $request->tgl_return,
                'alasan_retur'   => $request->alasan_retur,
                'barang_jual_id' => $penjualan->id,
                'keterangan'     => $request->keterangan,
                'status_retur'   => 'proses',
            ]);

            foreach ($request->items as $item) {
                // 3. Validasi: Apakah barang tersebut ada di transaksi penjualan dan jumlahnya cukup?
                $detailAsli = BarangJualDetail::where('barang_jual_id', $penjualan->id)
                    ->where('barang_id', $item['barang_id'])
                    ->first();

                if (!$detailAsli || $item['jumlah'] > $detailAsli->jumlah) {
                    throw new \Exception('Jumlah retur barang melebihi jumlah yang terjual.');
                }

                // 4. Simpan Detail Retur
                ReturDetail::create([
                    'return_id' => $return->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah'    => $item['jumlah'],
                ]);

                // 5. UPDATE STOK: Menambah kembali stok barang karena barang dikembalikan
                $barang = Barang::findOrFail($item['barang_id']);
                $barang->increment('stok', $item['jumlah']);
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur berhasil dicatat dan stok barang otomatis bertambah.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan retur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail retur.
     */
    public function show(ReturBarang $retur)
    {
        $retur->load('barangJual', 'details.barang');
        return view('retur.show', compact('retur'));
    }

    /**
     * Menampilkan form edit retur (Hanya status/tanggal).
     */
    public function edit(ReturBarang $retur)
    {
        return view('retur.edit', compact('retur'));
    }

    /**
     * Memperbarui data retur.
     */
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

        return redirect()->route('retur.show', $retur)->with('success', 'Data retur diperbarui.');
    }

    /**
     * Menghapus data retur dan menyesuaikan stok kembali.
     */
    public function destroy(ReturBarang $return)
    {
        DB::beginTransaction();
        try {
            // Jika data retur dihapus, maka stok barang yang tadinya bertambah harus dikurangi lagi
            foreach ($return->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->decrement('stok', $detail->jumlah);
                }
            }

            // Hapus detail dan header retur
            $return->details()->delete();
            $return->delete();  

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur dihapus dan stok barang telah dikurangi kembali.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus retur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal hapus retur.');
        }
    }

    /**
     * API untuk mengambil detail barang dari sebuah transaksi penjualan (untuk JavaScript).
     */
    public function getPenjualanDetails($id)
    {
        $details = BarangJualDetail::with('barang')->where('barang_jual_id', $id)->get();
        return response()->json($details);
    }
}