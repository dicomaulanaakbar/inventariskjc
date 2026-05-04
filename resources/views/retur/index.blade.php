@extends('layouts.app')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h2>Data Retur Barang</h2>
        <a href="{{ route('retur.create') }}" class="btn btn-primary">+ Retur Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr><th>ID Retur</th><th>Tanggal Retur</th><th>Penjualan ID</th><th>Alasan</th><th>Jumlah Item</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($returns as $ret)
                    <tr>
                        <td>{{ $ret->id }}</td>
                        <td>{{ $ret->tgl_return->format('d/m/Y') }}</td>
                        <td>{{ $ret->barang_jual_id }}</td>
                        <td>{{ ucfirst($ret->alasan_retur) }}</td>
                        <td>{{ $ret->details->sum('jumlah') }} pcs</td>
                        <td>
                            <a href="{{ route('retur.show', $ret) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('retur.edit', $ret) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('retur.destroy', $ret) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="6">Belum ada retur. <a href="{{ route('retur.create') }}">Buat retur</a></td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $returns->links() }}
        </div>
    </div>
</div>
@endsection
