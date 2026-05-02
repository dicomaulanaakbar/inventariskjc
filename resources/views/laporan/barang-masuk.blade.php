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
        {{-- Sinkronisasi nama variabel $totalPembelian sesuai Controller --}}
        <strong>Total Pembelian: Rp {{ number_format($totalPembelian, 0, ',', '.') }}</strong>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Biaya</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody> {{-- Tambahkan tag pembuka tbody yang hilang --}}
                    @foreach($pembelian as $beli)
                    <tr>
                        {{-- Gunakan parse jika tgl_pembelian bukan instance Carbon --}}
                        <td>{{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d/m/Y') }}</td>
                        <td>{{ $beli->supplier->nama_supplier ?? '-' }}</td>
                        <td>{{ $beli->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                        <td>{{ $beli->jumlah_barang }}</td>

                        {{-- PERBAIKAN HARGA SATUAN: Hitung otomatis agar tidak Rp 0 lagi --}}
                        <td>
                            Rp {{ number_format($beli->jumlah_barang > 0 ? ($beli->total_bayar / $beli->jumlah_barang) : 0, 0, ',', '.') }}
                        </td>

                        {{-- PERBAIKAN TOTAL: Gunakan kolom total_bayar sesuai database --}}
                        <td>Rp {{ number_format($beli->total_bayar, 0, ',', '.') }}</td>

                        <td>{{ $beli->user->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Pagination --}}
            <div class="mt-3">
                {{ $pembelian->appends(['start' => $start, 'end' => $end])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
