@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Retur Barang</div>
                <div class="card-body">
                    <form id="formRetur" method="POST" action="{{ route('retur.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Pilih Barang retur </label>
                            <select name="barang_jual_id" id="penjualan_id" class="form-control" required>
                                <option value="">Pilih Penjualan</option>

                                @foreach($penjualan as $pj)
                                    <option value="{{ $pj->id }}">
                                        Transaksi #{{ $pj->id }} - 
                                        {{ $pj->tgl_jual->format('d-m-Y') }} - 
                                        {{ $pj->details->count() }} item
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Retur</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Alasan Retur</label>
                            <select name="alasan" class="form-control" required>
                                <option value="rusak">Rusak</option>
                                <option value="salah_barang">Salah Barang</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Keterangan (opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>

                        <div id="items-container">
                            <h5>Barang yang diretur</h5>
                            <div class="alert alert-info" id="info-pilih-penjualan">Silakan pilih penjualan terlebih dahulu.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Retur</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('penjualan_id').addEventListener('change', function() {
        var penjualanId = this.value;
        var container = document.getElementById('items-container');
        if (!penjualanId) {
            container.innerHTML = '<div class="alert alert-info">Silakan pilih penjualan terlebih dahulu.</div>';
            return;
        }

        fetch('/retur/get-details/' + penjualanId)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<div class="alert alert-warning">Penjualan ini tidak memiliki detail barang.</div>';
                    return;
                }
                var html = '<h5>Barang yang diretur</h5>';
                data.forEach((item, idx) => {
                    html += `
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <input type="hidden" name="items[${idx}][barang_id]" value="${item.barang_id}">
                                <strong>${item.barang.nama_barang}</strong> (Terjual: ${item.jumlah})
                            </div>
                            <div class="col-md-3">
                                <label>Jumlah Retur</label>
                                <input type="number" name="items[${idx}][jumlah]" class="form-control" min="1" max="${item.jumlah}" required>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            })
            .catch(err => {
                container.innerHTML = '<div class="alert alert-danger">Gagal memuat detail penjualan.</div>';
                console.error(err);
            });
    });
</script>
@endpush
@endsection
