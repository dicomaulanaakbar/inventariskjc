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
    public function index(Request $request)
    {
        $returns = ReturBarang::with('barangJual', 'details.barang')
            ->orderBy('tgl_return', 'desc')
            ->paginate(15);
        return view('retur.index', compact('returns'));
    }

    public function create(Request $request)
    {
        // Ambil semua penjualan yang belum diretur sepenuhnya (opsional)
        $penjualan = BarangJual::with('details.barang')
            ->whereDoesntHave('return')
            ->orderBy('tgl_jual', 'desc')
            ->get();

        $barang = Barang::all();

        $barangs = BarangJual::with('details.barang')->get();

        return view('retur.create', compact('penjualan', 'barang', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_jual_id' => 'required|exists:barang_juals,id',
            'tanggal' => 'required|date',
            'alasan' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Ambil data penjualan
            $penjualan = BarangJual::findOrFail($request->barang_jual_id);

            // Buat retur header
            $return = ReturBarang::create([
                'tgl_return' => $request->tanggal,
                'alasan_retur' => $request->alasan,
                'barang_jual_id' => $penjualan->id,
                'keterangan' => $request->keterangan,
                'status_retur' => 'proses',
            ]);

            foreach ($request->items as $item) {
                // Validasi jumlah retur tidak melebihi jumlah yang dibeli
                $detailAsli = BarangJualDetail::where('barang_jual_id', $penjualan->id)
                    ->where('barang_id', $item['barang_id'])
                    ->first();
                if (!$detailAsli || $item['jumlah'] > $detailAsli->jumlah) {
                    throw new \Exception('Jumlah retur melebihi jumlah yang dibeli.');
                }

                // Simpan detail retur
                ReturDetail::create([
                    'return_id' => $return->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                ]);

                // Kembalikan stok barang (tambah stok)
                $barang = Barang::find($item['barang_id']);
                // $barang->increment('stok', $item['jumlah']);
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan retur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function show(ReturBarang $return)
    {
        $return->load('barangJual', 'details.barang');
        return view('retur.show', compact('return'));
    }

    public function edit(ReturBarang $return)
    {
        // Hanya admin/owner bisa edit (biasanya retur tidak diedit, tapi jika perlu)
        return view('retur.edit', compact('return'));
    }

    public function update(Request $request, ReturBarang $return)
    {
        $request->validate([
            'alasan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $return->update([
            'alasan_retur' => $request->alasan,
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('retur.show', $return)->with('success', 'Retur diperbarui.');
    }

    public function destroy(ReturBarang $return)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok (jika dihapus, stok harus dikurangi lagi? Atau biarkan saja?)
            // Karena retur sudah menambah stok, jika retur dihapus, stok harus dikurangi.
            foreach ($return->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                $barang->decrement('stok', $detail->jumlah);
            }
            $return->delete();
            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hapus retur.');
        }
    }

    // API: Ambil detail penjualan untuk dropdown items (AJAX)
    public function getPenjualanDetails($id)
    {
        $details = BarangJualDetail::with('barang')->where('barang_jual_id', $id)->get();
        return response()->json($details);
    }
}
