@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8"> <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold">
                    Tambah Retur Barang
                </div>

                <div class="card-body">
                    <form id="formRetur" method="POST" action="{{ route('retur.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" id="nama_barang"
                                class="form-control @error('nama_barang') is-invalid @enderror"
                                value="{{ old('nama_barang') }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="barang_id" class="form-label">Barang Id</label>
                            <input type="number" name="barang_id" id="barang_id"
                                class="form-control @error('barang_id') is-invalid @enderror"
                                value="{{ old('barang_id') }}" required>
                            @error('barang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tgl_return" class="form-label">Tanggal Retur</label>
                            <input type="date" name="tgl_return" id="tgl_return" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="alasan_retur">Alasan Retur</label>
                            <select id="alasan_retur" name="alasan_retur" class="form-control" required>
                                <option value="rusak">Rusak</option>
                                <option value="salah_kirim">Salah Kirim</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="sukses">Sukses</option>
                                <option value="proses">Proses</option>
                            </select>
                        </div>



                        <div class="mb-4">
                            <label class="form-label">Keterangan (opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan tambahan jika ada..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Simpan Retur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
