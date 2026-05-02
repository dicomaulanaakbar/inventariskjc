@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    Edit Penjualan
                </div>

                <div class="card-body">
                    <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Tanggal --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Penjualan</label>
                            <input type="date" name="tgl_jual" class="form-control"
                                value="{{ $penjualan->tgl_jual->format('Y-m-d') }}" required>
                        </div>

                        {{-- Barang --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <select name="barang_id" class="form-control" required>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}"
                                        {{ $barang->id == $penjualan->details->first()?->barang_id ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jumlah --}}
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control"
                                value="{{ $penjualan->details->first()?->jumlah ?? 0 }}" required>
                        </div>

                        {{-- Harga --}}
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control"
                                value="{{ $penjualan->details->first()?->barang?->harga_jual ?? 0 }}" required>
                        </div>

                        {{-- Total --}}
                        <div class="mb-3">
                            <label class="form-label">Total</label>
                            <input type="number" name="total" class="form-control"
                                value="{{ $penjualan->total ?? 0 }}" required>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-control" required>
                                <option value="qris" {{ $penjualan->metode_pembayaran == 'qris' ? 'selected' : '' }}>Qris</option>
                                <option value="tunai" {{ $penjualan->metode_pembayaran == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ $penjualan->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <!-- Tambahkan metode pembayaran lain jika diperlukan -->
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
