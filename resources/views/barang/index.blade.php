@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Daftar Barang</h2>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga Jual</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $barang->stok }}</td>
                <td>{{ $barang->satuan }}</td>
                <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('barang.riwayat', $barang) }}" class="btn btn-sm btn-info">Riwayat</a>
                    <form action="{{ route('barang.destroy', $barang) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $barangs->links() }}
</div>
@endsection