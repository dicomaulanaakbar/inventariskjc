@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Tambah Stok Keluar (Penjualan)</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('stok.keluar') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Pilih Barang</label>
                            <select name="barang_id" class="form-control" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $item)
                                    <option value="{{ $item->id }}" {{ (isset($barang) && $barang->id == $item->id) ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Harga Jual (per satuan)</label>
                            <input type="number" name="harga_jual_satuan" class="form-control" value="{{ old('harga_jual_satuan') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-control" required>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Keterangan (opsional)</label>
                            {{-- <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}"> --}}

                            <textarea type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}"></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger">Simpan Stok Keluar</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection