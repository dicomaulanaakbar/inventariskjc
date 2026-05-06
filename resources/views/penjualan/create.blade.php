@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Tambah Penjualan</div>
        <div class="card-body">

            <form action="{{ route('penjualan.store') }}" method="POST">
                @csrf

                <!-- Pilih Barang -->
                <div class="mb-3">
                    <label class="form-label">Pilih Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        <option value="">Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}"
                                data-harga="{{ $barang->harga }}"
                                data-stok="{{ $barang->stok }}">
                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="mb-3">
                    <label class="form-label">Tanggal Penjualan</label>
                    <input type="date" name="tgl_jual" class="form-control"
                           value="{{ date('Y-m-d') }}" required>
                </div>

                <!-- Harga (auto) -->
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="text" id="harga" class="form-control" readonly>
                </div>

                <!-- Jumlah -->
                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah"
                           class="form-control" min="1" required>
                    <small class="text-muted" id="stok-info"></small>
                </div>

                <!-- Total (auto) -->
                <div class="mb-3">
                    <label class="form-label">Total</label>
                    <input type="text" id="total" class="form-control" readonly>
                </div>

                <!-- Metode -->
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <option value="">Pilih Metode</option>
                        <option value="qris">QRIS</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
    let hargaInput = document.getElementById('harga');
    let jumlahInput = document.getElementById('jumlah');
    let totalInput = document.getElementById('total');
    let stokInfo = document.getElementById('stok-info');

    document.getElementById('barang_id').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];

        let harga = selected.getAttribute('data-harga');
        let stok = selected.getAttribute('data-stok');

        hargaInput.value = harga ? 'Rp ' + parseInt(harga).toLocaleString() : '';
        stokInfo.innerText = stok ? 'Stok tersedia: ' + stok : '';

        jumlahInput.max = stok;

        hitungTotal();
    });

    jumlahInput.addEventListener('input', hitungTotal);

    function hitungTotal() {
        let selected = document.getElementById('barang_id').options[document.getElementById('barang_id').selectedIndex];

        let harga = selected.getAttribute('data-harga');
        let jumlah = jumlahInput.value;

        if (harga && jumlah) {
            let total = harga * jumlah;
            totalInput.value = 'Rp ' + total.toLocaleString();
        } else {
            totalInput.value = '';
        }
    }
</script>
@endpush
@endsection
