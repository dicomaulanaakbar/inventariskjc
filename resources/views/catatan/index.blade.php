@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Catatan Barang</h2>
        {{-- <div>
            <a href="{{ route('stok.masuk.form', ['barang' => 0]) }}" class="btn btn-success">
                <i class="fas fa-arrow-down"></i> Stok Masuk
            </a>
            <a href="{{ route('stok.keluar.form') }}" class="btn btn-danger">
                <i class="fas fa-arrow-up"></i> Stok Keluar
            </a>
        </div> --}}
    </div>

    <!-- Filter Tanggal -->
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
            <a href="{{ route('catatan.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Ringkasan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Pendapatan</h5>
                    <h4>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Total Pengeluaran (Pembelian)</h5>
                    <h4>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Laba Kotor</h5>
                    <h4>Rp {{ number_format($labaKotor, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Penjualan -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Penjualan (Stok Keluar)</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr><th>Tanggal</th><th>Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Subtotal</th><th>Metode</th><th>Petugas</th></tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $jual)
                        @foreach($jual->details as $detail)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($jual->tgl_jual)->format('d/m/Y') }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}</td>
                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($jual->metode_pembayaran) }}</td>
                            <td>{{ $jual->user->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr><td colspan="7">Tidak ada penjualan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabel Pembelian -->
    <!-- Tabel Pembelian -->
<div class="card">
    <div class="card-header bg-success text-white">Pembelian (Stok Masuk)</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
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

                    {{-- PERBAIKAN DI SINI: Menggunakan harga_beli dari relasi barang agar sinkron --}}
                    <td>Rp {{ number_format($beli->barang->harga_beli ?? 0, 0, ',', '.') }}</td>

                    {{-- PERBAIKAN DI SINI: Menggunakan total_bayar (sesuai nama kolom di DB) --}}
                    <td>Rp {{ number_format($beli->total_bayar ?? 0, 0, ',', '.') }}</td>

                    <td>{{ $beli->user->name ?? $beli->user_id }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada pembelian pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
