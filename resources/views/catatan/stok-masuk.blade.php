@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Tambah Stok Masuk (Pembelian)</div>
                <div class="card-body">
                    <form action="{{ route('stok.masuk', $barang) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" value="{{ $barang->nama_barang }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Harga Beli per Satuan</label>
                            <input type="number" name="harga_beli_satuan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Keterangan (Opsional)</label>
                            <input type="text" name="keterangan" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection