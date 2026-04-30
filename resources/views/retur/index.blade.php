@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center">
                    <span>Daftar Retur Barang</span>
                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'owner')
                    <a href="{{ route('retur.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Retur
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
                                <th>Tanggal Retur</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returs as $retur)
                                <tr>
                                    <td>{{ $retur->id }}</td>
                                    {{--<td>{{ $retur->details->first()->barang->nama_barang ?? 'N/A' }}</td>--}}
                                    <td>{{ $retur->tgl_return }}</td>
                                    <td>{{ $retur->alasan_retur }}</td>
                                    <td>{{ ucfirst($retur->status) }}</td>
                                    <td>
                                        <a href="{{ route('retur.show', $retur->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'owner')
                                        <a href="{{ route('retur.edit', $retur->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                        <!-- Form Hapus -->
                                        <form action="{{ route('retur.destroy', $retur->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus retur ini?');">
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
