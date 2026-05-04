@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Retur</h1>

    <form action="{{ route('retur.update', $retur) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Tanggal -->
        <div class="form-group mb-3">
            <label>Tanggal Retur</label>
            <input type="date"
                   class="form-control"
                   name="tgl_return"
                   value="{{ optional($retur->tgl_retur)->format('Y-m-d') }}"
                   required>
        </div>

                            <!-- Alasan -->
                            <div class="mb-3">
                            <label>Alasan Retur</label>
                            <select name="alasan_retur" class="form-control" required>
                                 <option value="" disabled selected>Pilih Alasan</option>
                                <option value="rusak">Rusak</option>
                                <option value="salah_barang">Salah Barang</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

        <!-- Status -->
        <div class="form-group mb-3">
            <label>Status</label>
            <select class="form-control" name="status_retur" required>
                <option value="proses" {{ $retur->status_retur == 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ $retur->status_retur == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('retur.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
