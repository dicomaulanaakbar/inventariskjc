@extends('layouts.app')

@section('content')
 <form action="{{ route('penjualan.store') }}" method="POST">
        @csrf
        <!-- Form fields for creating a new sale -->
        <div class="mb-3">
            <label for="barang_id" class="form-label">Pilih Barang</label>
            <select name="barang_id" id="barang_id" class="form-control" required>
                <option value="">Pilih Barang</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="tgl_jual" class="form-label">Tanggal Penjualan</label>
            <input type="date" name="tgl_jual" id="tgl_jual" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total</label>
            <input type="number" name="total" id="total" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="metode_pembayaran" class="form-label">Metode pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                <option value="">Pilih Metode Pembayaran</option>
                <option value="Qris">Qris</option>
                <option value="Tunai">Tunai</option>
                <option value="Transfer">Transfer</option>
                <!-- Add more payment methods as needed -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection
