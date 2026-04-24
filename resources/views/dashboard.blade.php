@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <span class="badge bg-secondary">KJC STOK</span>
    </div>

    <!-- Ringkasan Cards -->
    <div class="row">
        <!-- Total Barang -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Barang</div>
                            {{-- <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBarang }}</div> --}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Kurang -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stok Kurang (≤5)</div>
                            {{-- <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stokKurang }}</div> --}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Masuk (Bulan Ini) -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Barang Masuk (Bulan Ini)</div>
                            {{-- <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $barangMasuk }}</div> --}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Keluar (Bulan Ini) -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Barang Keluar (Bulan Ini)</div>
                            {{-- <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $barangKeluar }}</div> --}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Barang -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Barang (Stok Rendah)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Deskripsi</th>
                            <th>Harga (Jual)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $index => $barang)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>
                                @if($barang->deskripsi)
                                    {{ $barang->deskripsi }}
                                @else
                                    {{ $barang->kategori->nama_kategori ?? '-' }}
                                @endif
                            </td>
                            <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                @if($barang->stok <= 5)
                                    <span class="badge bg-danger">Stok Habis / Kurang</span>
                                @elseif($barang->stok <= 10)
                                    <span class="badge bg-warning">Menipis</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('barang.show', $barang->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">Belum ada data barang</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection