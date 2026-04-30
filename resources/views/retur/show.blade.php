@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Retur Barang</h2>

    <div class="card">
        <div class="card-header">
            <strong>Form Retur</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('retur.store') }}" method="POST">
                @csrf

                <input type="hidden" name="barang_id" value="{{ $barang->id }}">

                <div class="mb-3">
                    <label for="tgl_retur" class="form-label">Tanggal Retur</label>
                    <input type="date" name="tgl_retur" id="tgl_retur"
                           class="form-control @error('tgl_retur') is-invalid @enderror"
                           value="{{ old('tgl_retur') }}" required>
                    @error('tgl_retur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="alasan" class="form-label">Alasan Retur</label>
                    <textarea name="alasan" id="alasan" rows="3"
                              class="form-control @error('alasan') is-invalid @enderror" required>{{ old('alasan') }}</textarea>
                    @error('alasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jumlah retur (opsional) --}}
                <!--
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Retur</label>
                    <input type="number" name="jumlah" id="jumlah"
                           class="form-control @error('jumlah') is-invalid @enderror"
                           value="{{ old('jumlah', 1) }}" min="1">
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                -->

                <button type="submit" class="btn btn-primary">Submit Retur</button>
            </form>
        </div>
    </div>

