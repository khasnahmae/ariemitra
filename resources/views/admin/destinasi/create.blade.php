@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Tambah Destinasi Wisata Baru</h4>
            <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary btn-round">
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

        <form action="{{ route('admin.destinasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="nama_destinasi" class="fw-bold">Nama Objek Wisata <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nama_destinasi" class="form-control" id="nama_destinasi"
                            value="{{ old('nama_destinasi') }}" placeholder="Contoh: Candi Borobudur" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="lokasi" class="fw-bold">Lokasi / Kota <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" class="form-control" id="lokasi" value="{{ old('lokasi') }}"
                            placeholder="Contoh: Magelang, Jawa Tengah" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="htm_per_orang" class="fw-bold">Tarif HTM Per Orang (Rp) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="htm_per_orang" class="form-control" id="htm_per_orang" min="0"
                            value="{{ old('htm_per_orang') }}" placeholder="Contoh: 50000" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="foto" class="fw-bold">Foto Objek Wisata (Opsional)</label>
                        <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, WEBP (Maksimal: 2 MB)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="keterangan" class="fw-bold">Keterangan / Catatan Objek Wisata (Opsional)</label>
                        <textarea name="keterangan" class="form-control" id="keterangan" rows="3"
                            placeholder="Informasi jam operasional atau fasilitas pendukung...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-save me-1"></i> Simpan Destinasi
                </button>
                <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
