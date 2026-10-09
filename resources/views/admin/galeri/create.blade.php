@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Unggah Foto Dokumentasi Baru</h4>
            <a href="{{ route('admin.galeri.index') }}" class="btn btn-outline-secondary btn-round">
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

        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid">
                <div class="col">

                    <div class="form-group">
                        <label for="judul" class="fw-bold">Judul Dokumentasi <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" id="judul" value="{{ old('judul') }}"
                            placeholder="Contoh: Rombongan PT Jaya di Candi Borobudur" required>
                    </div>

                    <div class="form-group">
                        <label for="paket_id" class="fw-bold">Hubungkan ke Paket Wisata (Opsional)</label>
                        <select name="paket_id" class="form-select" id="paket_id">
                            <option value="">-- Pilih Paket Wisata (Opsional) --</option>
                            @foreach ($paketList as $paket)
                                <option value="{{ $paket->id }}" {{ old('paket_id') == $paket->id ? 'selected' : '' }}>
                                    {{ $paket->nama_paket }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="file_gambar" class="fw-bold">Pilih File Gambar (JPG/PNG/WEBP) <span
                                class="text-danger">*</span></label>
                        <input type="file" name="file_gambar" class="form-control" id="file_gambar" accept="image/*"
                            required>
                        <small class="text-muted">Maksimal ukuran file: 2 MB</small>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-upload me-1"></i> Unggah Foto
                </button>
                <a href="{{ route('admin.galeri.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
