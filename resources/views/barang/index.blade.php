@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Barang</h2>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    </div>

    <!-- Form Pencarian & Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('barang.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Nama Barang</label>
                    <input type="text" name="search" class="form-control" placeholder="Ketik nama barang..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-3">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        {{-- <th>Supplier</th> --}}
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga Jual</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr>
                        <td>{{ $barang->id }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                        {{-- <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td> --}}
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
                            <a href="{{ route('stok.masuk.form', $barang->id) }}" class="btn btn-sm btn-success">Masuk
                                {{-- <i class="fas fa-arrow-down"></i> --}}
                            </a>
                            <a href="{{ route('stok.keluar.form', $barang->id) }}" class="btn btn-sm btn-danger">Keluar
                                {{-- <i class="fas fa-arrow-up"></i> --}}
                            </a>
                            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-warning">Edit                                
                                {{-- <i class="fas fa-edit"></i> --}}
                            </a>
                            <form action="{{ route('barang.destroy', $barang) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus
                                    {{-- <i class="fas fa-trash"></i> --}}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $barangs->links() }}
        </div>
    </div>
</div>
@endsection