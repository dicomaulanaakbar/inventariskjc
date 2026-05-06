@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Detail Penjualan #{{ $penjualan->id }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Tanggal Jual</th>
                            <td>: {{ \Carbon\Carbon::parse($penjualan->tgl_jual)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>: {{ ucfirst($penjualan->metode_pembayaran) }}</td>
                        </tr>
                        <tr>
                            <th>Total Harga Jual</th>
                            <td>: Rp {{ number_format($penjualan->total_harga_jual, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Petugas (Kasir)</th>
                            <td>: {{ $penjualan->user->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <h5>Daftar Barang yang Dijual</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}</td>
                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali ke Daftar Penjualan</a>
                <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-warning">Edit Penjualan</a>
            </div>
        </div>
    </div>
</div>
@endsection