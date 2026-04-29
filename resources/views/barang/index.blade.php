@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Barang</h2>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr>
                        <td>{{ $barang->formatted_id ?? $barang->id }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                        <td>
                            @if($barang->stok <= 5)
                                <span class="badge bg-danger">{{ $barang->stok }}</span>
                            @else
                                {{ $barang->stok }}
                            @endif
                        </td>
                        <td>{{ $barang->satuan }}</td>
                        <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                        <td>
                            <!-- Tombol Stok Masuk -->
                            <a href="{{ route('stok.masuk.form', $barang->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-arrow-down"></i> Masuk
                            </a>
                            <!-- Tombol Stok Keluar -->
                            <a href="{{ route('stok.keluar.form', $barang->id) }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-arrow-up"></i> Keluar
                            </a>
                            <!-- Tombol Edit -->
                            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Tombol Hapus -->
                            <form action="{{ route('barang.destroy', $barang) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus barang ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data barang. <a href="{{ route('barang.create') }}">Tambah sekarang</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $barangs->links() }}
        </div>
    </div>
</div>
@endsection