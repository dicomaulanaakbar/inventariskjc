@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Laporan Keuangan (Pendapatan Penjualan)</h2>

    <!-- Form Filter Tanggal -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Dari Tanggal</label>
            <input type="date" name="start" class="form-control" value="{{ $start }}">
        </div>
        <div class="col-md-3">
            <label>Sampai Tanggal</label>
            <input type="date" name="end" class="form-control" value="{{ $end }}">
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('laporan.keuangan') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Total Pendapatan -->
    <div class="alert alert-success">
        <strong>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
    </div>

    <!-- Tabel Penjualan -->
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Metode Pembayaran</th>
                        <th>Total Harga</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $jual)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($jual->tgl_jual)->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst($jual->metode_pembayaran) }}</td>
                        <td>Rp {{ number_format($jual->total_harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $jual->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Tidak ada transaksi penjualan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $penjualan->appends(['start' => $start, 'end' => $end])->links() }}
        </div>
    </div>
</div>
@endsection