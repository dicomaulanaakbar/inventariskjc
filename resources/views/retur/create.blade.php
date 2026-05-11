@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Retur Barang</h4>
                </div>

                <div class="card-body">

                    {{-- ERROR VALIDASI --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="formRetur" method="POST" action="{{ route('retur.store') }}">
                        @csrf

                        {{-- PILIH PENJUALAN --}}
                        <div class="mb-3">
                            <label class="form-label">Pilih Transaksi Penjualan</label>

                            <select name="barang_jual_id" id="penjualan_id" class="form-control" required>
                                <option value=""> Pilih Penjualan </option>

                                @foreach($penjualan as $pj)
                                    {{-- Tambahkan atribut data-tgl untuk menyimpan tanggal asli transaksi --}}
                                    <option value="{{ $pj->id }}" data-tgl="{{ $pj->tgl_jual->format('Y-m-d') }}">
                                        Transaksi #{{ $pj->id }}
                                        | {{ $pj->tgl_jual->format('d-m-Y') }}
                                        | {{ $pj->details->count() }} Item
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TANGGAL RETUR (Sekarang Menjadi Dropdown Otomatis) --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Retur</label>
                            <select name="tgl_return" id="tgl_return" class="form-control" required>
                                <option value="">-- Pilih Transaksi Dahulu --</option>
                            </select>
                            <small class="text-muted">Tanggal retur disesuaikan otomatis dengan tanggal transaksi.</small>
                        </div>

                        {{-- ALASAN --}}
                        <div class="mb-3">
                            <label class="form-label">Alasan Retur</label>

                            <select name="alasan_retur" class="form-control" required>
                                <option value="">-- Pilih Alasan --</option>
                                <option value="rusak">Rusak</option>
                                <option value="salah_barang">Salah Barang</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="mb-3">
                            <label class="form-label">Keterangan (Opsional)</label>

                            <textarea
                                name="keterangan"
                                rows="3"
                                class="form-control"
                            >{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-3">
                            <label class="form-label">Status Retur</label>

                            <select name="status_retur" class="form-control" required>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>

                        {{-- DETAIL BARANG --}}
                        <div id="items-container">
                            <div class="alert alert-info">
                                Silakan pilih transaksi penjualan terlebih dahulu.
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Simpan Retur
                            </button>

                            <a href="{{ route('retur.index') }}" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

document.getElementById('penjualan_id').addEventListener('change', function () {

    let selectedOption = this.options[this.selectedIndex];
    let penjualanId = this.value;
    let tglTransaksi = selectedOption.getAttribute('data-tgl'); // Ambil tanggal dari atribut data

    let container = document.getElementById('items-container');
    let dropdownTglRetur = document.getElementById('tgl_return');

    // 1. Logika Sinkronisasi Dropdown Tanggal
    dropdownTglRetur.innerHTML = ''; // Reset isi dropdown

    if (!penjualanId) {
        // Jika tidak ada penjualan dipilih
        dropdownTglRetur.innerHTML = '<option value="">-- Pilih Transaksi Dahulu --</option>';
        container.innerHTML = `
            <div class="alert alert-info">
                Silakan pilih transaksi penjualan terlebih dahulu.
            </div>
        `;
        return;
    }

    // Masukkan tanggal transaksi ke dalam dropdown tanggal retur
    let opt = document.createElement('option');
    opt.value = tglTransaksi;
    opt.text = tglTransaksi;
    opt.selected = true;
    dropdownTglRetur.appendChild(opt);


    // 2. Logika Fetch Detail Barang (AJAX)
    fetch('/retur/get-details/' + penjualanId)
        .then(response => response.json())
        .then(data => {

            if (data.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-warning">
                        Tidak ada detail barang pada transaksi ini.
                    </div>
                `;
                return;
            }

            let html = `
                <h5 class="mb-3">Barang Yang Diretur</h5>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Barang</th>
                            <th width="150">Jumlah Dibeli</th>
                            <th width="150">Jumlah Retur</th>
                        </tr>
                    </thead>

                    <tbody>
            `;

            data.forEach((item, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            ${item.barang.nama_barang}
                            <input type="hidden" name="items[${index}][barang_id]" value="${item.barang_id}">
                        </td>
                        <td class="text-center">
                            ${item.jumlah}
                        </td>
                        <td>
                            <input
                                type="number"
                                name="items[${index}][jumlah]"
                                class="form-control"
                                min="1"
                                max="${item.jumlah}"
                                placeholder="Maks: ${item.jumlah}"
                                required
                            >
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            container.innerHTML = html;
        })
        .catch(error => {
            console.error(error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    Gagal memuat detail penjualan.
                </div>
            `;
        });

});
</script>
@endpush

@endsection
