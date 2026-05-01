@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center">
                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'owner')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2>Daftar Penjualan</h2>
                        </div>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Penjualan
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Tanggal Penjualan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualans as $penjualan)
                                    <tr>
                                    <td>{{ $penjualan->id }}</td>
                                    <td>{{ $penjualan->details->first()?->barang?->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $penjualan->tgl_jual->format('d-m-Y') }}</td>
                                    <td>{{ $penjualan->details->first()?->jumlah ?? 0 }}</td>
                                    <td>Rp {{ number_format($penjualan->details->first()?->barang?->harga_jual ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format(($penjualan->details->first()?->jumlah ?? 0) *($penjualan->details->first()?->barang?->harga_jual ?? 0), 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'owner')
                                        <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                        <!-- Form Hapus -->
                                        <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penjualan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
