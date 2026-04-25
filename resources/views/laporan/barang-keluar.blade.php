@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Laporan Barang Keluar (Penjualan)</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Dari Tanggal</label>
            <input type="date" name="start" class="form-control" value="{{ $start }}">
        </div>
        <div class="col-md-3">
            <label>Sampai Tanggal</label>
            <input type="date" name="end" class="form-control" value="{{ $end }}">
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('laporan.barang-keluar') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="alert alert-warning">
        <strong>Total Barang Terjual: {{ $totalTerjual }} pcs</strong>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal Penjualan</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Terjual</th>
                        <th>Harga Jual</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailPenjualan as $detail)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($detail->barangJual->tgl_jual)->format('d/m/Y') }}</td>
                        <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                        <tr><td colspan="5">Tidak ada penjualan barang pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $detailPenjualan->appends(['start' => $start, 'end' => $end])->links() }}
        </div>
    </div>
</div>
@endsection