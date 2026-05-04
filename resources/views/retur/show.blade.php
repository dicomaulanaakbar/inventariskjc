@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Detail Retur #{{ $retur->id }}</h5>
        </div>

        <div class="card-body">

            <!-- INFORMASI UTAMA -->
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Tanggal Retur</th>
                    <td>
                        {{ $retur->tgl_return
                            ? \Carbon\Carbon::parse($retur->tgl_return)->format('d/m/Y')
                            : '-'
                        }}
                    </td>
                </tr>

                <tr>
                    <th>Penjualan ID</th>
                    <td>{{ $retur->barang_jual_id ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Alasan</th>
                    <td>{{ $retur->alasan_retur ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Keterangan</th>
                    <td>{{ $retur->keterangan ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ $retur->status_retur == 'selesai' ? 'success' : 'warning' }}">
                            {{ ucfirst($retur->status_retur) }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Total Item Retur</th>
                    <td>{{ $retur->details->sum('jumlah') }} pcs</td>
                </tr>
            </table>

            <!-- DETAIL BARANG -->
            <h5 class="mt-4">Barang yang Diretur</h5>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama Barang</th>
                        <th width="150">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($retur->details as $detail)
                        <tr>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->jumlah }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">
                                Tidak ada data barang retur
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- BUTTON -->
            <div class="mt-3">
                <a href="{{ route('retur.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('retur.edit', $retur) }}" class="btn btn-warning">Edit</a>
            </div>

        </div>
    </div>
</div>
@endsection
