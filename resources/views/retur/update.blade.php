@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Retur</h1>

    {{-- Error validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('retur.update', $return->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Tanggal Retur -->
        <div class="form-group mb-3">
            <label>Tanggal Retur</label>
            <input
                type="date"
                name="tgl_return"
                class="form-control"
                value="{{ old('tgl_return', optional($return->tgl_return)->format('Y-m-d')) }}"
                required
            >
        </div>

        <!-- Alasan Retur -->
        <div class="form-group mb-3">
            <label>Alasan Retur</label>
            <select name="alasan_retur" class="form-control" required>
                <option value="">-- Pilih Alasan --</option>
                <option value="rusak" {{ old('alasan_retur', $return->alasan_retur) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="salah_barang" {{ old('alasan_retur', $return->alasan_retur) == 'salah_barang' ? 'selected' : '' }}>Salah Barang</option>
                <option value="kadaluarsa" {{ old('alasan_retur', $return->alasan_retur) == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                <option value="lainnya" {{ old('alasan_retur', $return->alasan_retur) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        <!-- Keterangan -->
        <div class="form-group mb-3">
            <label>Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control">{{ old('keterangan', $return->keterangan) }}</textarea>
        </div>

        <!-- Status -->
        <div class="form-group mb-3">
            <label>Status</label>
            <select name="status_retur" class="form-control" required>
                <option value="proses" {{ old('status_retur', $return->status_retur) == 'proses' ? 'selected' : '' }}>
                    Proses
                </option>
                <option value="selesai" {{ old('status_retur', $return->status_retur) == 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('retur.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
