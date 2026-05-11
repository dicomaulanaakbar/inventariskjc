{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Upload File (Uji Path Traversal)</h4>
            <p class="mb-0 text-white-50">Halaman ini sengaja dibuat untuk menguji kerentanan Path Traversal. File disimpan dengan nama asli.</p>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}<br>
                    <small>Lokasi: {{ session('path') }}</small>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Pilih File (jpg, png, pdf, txt - maks 2MB)</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
                <a href="{{ route('upload.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Informasi Pengujian Path Traversal</div>
        <div class="card-body">
            <p>Gunakan <strong>Burp Suite</strong> untuk mengubah nama file saat upload menjadi payload traversal, misalnya:</p>
            <ul>
                <li><code>../../../.env</code></li>
                <li><code>..%2f..%2f..%2f.env</code></li>
                <li><code>..\\..\\..\\.env</code></li>
            </ul>
            <p>Setelah upload, coba akses file melalui URL:</p>
            <code>{{ url('storage/uploads/') }}/[nama_file]</code>
            <p class="mt-2 text-danger">⚠️ <strong>Catatan:</strong> Fitur ini hanya untuk pengujian keamanan. Jangan digunakan di produksi.</p>
        </div>
    </div>
</div>
@endsection --}}