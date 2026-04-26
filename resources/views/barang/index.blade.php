@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Barang</h5>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>

         <a href="{{ route('stok.keluar.form') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-arrow-up"></i> Barang Keluar
        </a>

        <a href="{{ route('stok.masuk.form', ['barang' => 0]) }}" class="btn btn-success btn-sm">
            <i class="fas fa-arrow-down"></i> Barang Masuk
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Spesifikasi</th>
                    <th>Supplier</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($barangs as $b)
                <tr>
                    <td>{{ $b->id }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->spesifikasi }}</td>
                    <td>{{ $b->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                    <td>
                        <a href="{{ route('barang.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('barang.destroy', $b->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data</td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>
</div>

@endsection