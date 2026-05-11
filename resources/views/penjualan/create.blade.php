@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">Tambah Penjualan</div>
        <div class="card-body">

            <form action="{{ route('penjualan.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Pilih Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        <option value="">-- Ketik Nama Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}"
                                data-harga="{{ $barang->harga }}"
                                data-stok="{{ $barang->stok }}">
                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Penjualan</label>
                    <input type="date" name="tgl_jual" class="form-control"
                           value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Satuan</label>
                        <input type="text" id="harga" class="form-control" readonly placeholder="Rp 0">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jumlah Beli</label>
                        <input type="number" name="jumlah" id="jumlah"
                               class="form-control" min="1" required placeholder="0">
                        <small class="text-danger" id="stok-info"></small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold">Total Pembayaran</label>
                    <input type="text" id="total" class="form-control form-control-lg" readonly style="background-color: #e9ecef; font-weight: bold;" placeholder="Rp 0">
                </div>

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <option value="">Pilih Metode</option>
                        <option value="qris">QRIS</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                </div>
            </form>

        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi Autocomplete pada dropdown barang
        $('#barang_id').select2({
            placeholder: "Cari nama barang...",
            allowClear: true,
            width: '100%'
        });

        let hargaInput = document.getElementById('harga');
        let jumlahInput = document.getElementById('jumlah');
        let totalInput = document.getElementById('total');
        let stokInfo = document.getElementById('stok-info');

        // 2. Fungsi saat barang dipilih
        $('#barang_id').on('change', function() {
            let selected = $(this).find(':selected');
            
            let harga = selected.data('harga');
            let stok = selected.data('stok');

            // Update UI Harga dan info stok
            if (harga) {
                hargaInput.value = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                stokInfo.innerText = 'Stok tersedia: ' + stok;
                jumlahInput.max = stok; // Batasi input maksimal sesuai stok
            } else {
                hargaInput.value = '';
                stokInfo.innerText = '';
            }
            
            // Reset jumlah jika barang diganti
            jumlahInput.value = '';
            totalInput.value = '';
        });

        // 3. Fungsi hitung total saat jumlah diketik
        jumlahInput.addEventListener('input', function() {
            let selected = $('#barang_id').find(':selected');
            let harga = selected.data('harga');
            let stok = selected.data('stok');
            let jumlah = parseInt(this.value);

            // Validasi jangan melebihi stok
            if (jumlah > stok) {
                alert('Jumlah melebihi stok yang tersedia!');
                this.value = stok;
                jumlah = stok;
            }

            if (harga && jumlah) {
                let total = harga * jumlah;
                totalInput.value = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                totalInput.value = '';
            }
        });
    });
</script>
@endpush
@endsection