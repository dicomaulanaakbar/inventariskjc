@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Detail Retur #{{ $return->id }}</div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>Tanggal Retur</th><td>
    {{ $return->tgl_return 
        ? $return->tgl_return->format('d/m/Y H:i') 
        : '-' 
    }}
</td>
                <tr><th>Penjualan ID</th><td>{{ $return->barang_jual_id }}</td></tr>
                <tr><th>Alasan</th><td>{{ ucfirst($return->alasan_retur) }}</td></tr>
                <tr><th>Keterangan</th><td>{{ $return->keterangan ?? '-' }}</td></tr>
                <tr><th>Total Item Retur</th><td>{{ $return->details->sum('jumlah') }} pcs</td></tr>
            </table>

            <h5>Barang yang diretur</h5>
            <table class="table table-sm">
                <thead><tr><th>Barang</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @foreach($return->details as $detail)
                    <tr><td>{{ $detail->barang->nama_barang }}</td><td>{{ $detail->jumlah }}</td></tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('retur.index') }}" class="btn btn-secondary">Kembali</a>
            @if($return && $return->id)
    <a href="{{ route('retur.edit', $return->id) }}" class="btn btn-warning">Edit</a>
@endif
        </div>
    </div>
</div>
@endsection