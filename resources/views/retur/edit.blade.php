@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Retur</h1>
        <form action="{{ route('retur.update', $retur->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="tgl_retur">Tanggal Retur</label>
                <input type="date" class="form-control" id="tgl_retur" name="tgl_retur" value="{{ $retur->tgl_retur }}" required>
            </div>
            <div class="form-group">
                <label for="alasan">Alasan</label>
                <textarea class="form-control" id="alasan" name="alasan" required>{{ $retur->alasan }}</textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    @foreach($statusOptions as $option)
                        <option value="{{ $option }}" {{ $retur->status === $option ? 'selected' : '' }}>{{ ucfirst($option) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('retur.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
