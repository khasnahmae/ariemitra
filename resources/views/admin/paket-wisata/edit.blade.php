@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Edit Kategori Paket Wisata</h4>
            <a href="{{ route('admin.paket-wisata.index') }}" class="btn btn-outline-secondary btn-round">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.paket-wisata.update', $paketWisata->uuid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border p-3">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="nomor_paket" class="fw-bold">Nomor Paket</label>
                        <input type="text" id="nomor_paket" class="form-control bg-light fw-bold text-primary"
                            value="Paket {{ $paketWisata->nomor_paket }}" readonly disabled>
                        <small class="text-muted">Nomor paket bersifat tetap dan tidak dapat diubah.</small>
                    </div>

                    <div class="col-md-12">
                        <label for="nama_paket" class="fw-bold">Nama Paket Wisata <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" class="form-control" id="nama_paket"
                            value="{{ old('nama_paket', $paketWisata->nama_paket) }}"
                            placeholder="Contoh: Cirebon, Semarang" required>
                    </div>
                </div>

                <div class="d-flex gap-2 pt-4">
                    <button type="submit" class="btn btn-secondary btn-round">
                        <i class="fas fa-save me-1"></i> Perbarui Paket Wisata
                    </button>
                    <a href="{{ route('admin.paket-wisata.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection
