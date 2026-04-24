@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Detail Barang</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th>Kode</th><td>{{ $barang->kode_barang }}</td></tr>
                        <tr><th>Nama</th><td>{{ $barang->nama_barang }}</td></tr>
                        <tr><th>Kategori</th><td>{{ $barang->kategori->nama_kategori ?? '-' }}</td></tr>
                        <tr><th>Supplier</th><td>{{ $barang->supplier->nama_supplier ?? '-' }}</td></tr>
                        {{-- <tr><th>Stok</th><td>{{ $barang->stok }} {{ $barang->satuan }}</td></tr>
                        <tr><th>Harga Beli</th><td>Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td></tr>
                        <tr><th>Harga Jual</th><td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td></tr> --}}
                    </table>
                    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Riwayat Transaksi</div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button">Stok Masuk</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar" type="button">Stok Keluar</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="masuk">
                            @if($barang->barangBeli->count())
                                <ul>
                                    @foreach($barang->barangBeli as $beli)
                                        <li>{{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d/m/Y') }} - +{{ $beli->jumlah_barang }} (Rp {{ number_format($beli->total_biaya,0,',','.') }})</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Belum ada stok masuk.</p>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="keluar">
                            @if($barang->barangJualDetails->count())
                                <ul>
                                    @foreach($barang->barangJualDetails as $jual)
                                        <li>{{ \Carbon\Carbon::parse($jual->barangJual->tgl_jual)->format('d/m/Y') }} - -{{ $jual->jumlah }} (Rp {{ number_format($jual->subtotal,0,',','.') }})</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Belum ada stok keluar.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection