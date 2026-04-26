@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Tambah Stok Keluar (Penjualan)</div>
                <div class="card-body">
                    <form action="{{ route('stok.keluar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Pilih Barang</label>
                            <select name="barang_id" class="form-control" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $item)
                                    <option value="{{ $item->id }}" {{ isset($barang) && $barang->id == $item->id ? 'selected' : '' }}>{{ $item->nama_barang }} (Stok: {{ $item->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Harga Jual per Satuan</label>
                            <input type="number" name="harga_jual_satuan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-control">
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="cicil">Cicil</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Simpan</button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection