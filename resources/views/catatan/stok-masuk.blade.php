@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">Tambah Stok Masuk (Pembelian)</div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ isset($barang) ? route('stok.masuk', $barang->id) : route('stok.masuk') }}" method="POST">
                        @csrf

                        @if(isset($barang))
                            <div class="mb-3">
                                <label>Barang</label>
                                <input type="text" class="form-control" value="{{ $barang->nama_barang }}" disabled>
                                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                            </div>
                        @else
                            <div class="mb-3">
                                <label>Pilih Barang</label>
                                <select name="barang_id" class="form-control" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $item)
                                        <option value="{{ $item->id }}" {{ old('barang_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control">
                        <option value="">-- Pilih Supplier  --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                        <div class="mb-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Harga Beli (per satuan)</label>
                            <input type="number" name="harga_beli_satuan" class="form-control" value="{{ old('harga_beli_satuan') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Keterangan (opsional)</label>
                            <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Stok Masuk</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection