@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Laporan Barang Masuk (Pembelian)</h2>

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
            <a href="{{ route('laporan.barang-masuk') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="alert alert-info">
        <strong>Total Pembelian: Rp {{ number_format($totalPembelian, 0, ',', '.') }}</strong>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Biaya</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelian as $beli)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d/m/Y') }}</td>
                        <td>{{ $beli->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $beli->jumlah_barang }} {{ $beli->barang->satuan ?? '' }}</td>
                        <td>Rp {{ number_format($beli->total_biaya / $beli->jumlah_barang, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($beli->total_biaya, 0, ',', '.') }}</td>
                        <td>{{ $beli->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                        <tr><td colspan="6">Tidak ada data pembelian.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $pembelian->appends(['start' => $start, 'end' => $end])->links() }}
        </div>
    </div>
</div>
@endsection