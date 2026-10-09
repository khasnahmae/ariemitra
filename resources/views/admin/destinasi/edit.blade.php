@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Edit Destinasi Wisata</h4>
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

        <form action="{{ route('admin.destinasi.update', $destinasi->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="container-fluid">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="nama_destinasi" class="fw-bold">Nama Objek Wisata <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nama_destinasi" class="form-control" id="nama_destinasi"
                            value="{{ old('nama_destinasi', $destinasi->nama_destinasi) }}"
                            placeholder="Contoh: Candi Borobudur" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="lokasi" class="fw-bold">Lokasi / Kota <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi" class="form-control" id="lokasi"
                            value="{{ old('lokasi', $destinasi->lokasi) }}" placeholder="Contoh: Magelang, Jawa Tengah"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="htm_per_orang" class="fw-bold">Tarif HTM Per Orang (Rp) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="htm_per_orang" class="form-control" id="htm_per_orang" min="0"
                            value="{{ old('htm_per_orang', (int) $destinasi->htm_per_orang) }}" placeholder="Contoh: 50000"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold d-block mb-2">Foto saat ini</label>
                        @if ($destinasi->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $destinasi->foto) }}" alt="{{ $destinasi->nama_destinasi }}"
                                    class="img-thumbnail rounded shadow-sm" style="max-height: 180px; object-fit: cover;">
                            </div>
                        @else
                            <p class="text-muted small">Belum ada foto yang diunggah.</p>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="foto" class="fw-bold">Ganti Foto Objek Wisata (Opsional)</label>
                        <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
                        <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG,
                            PNG, WEBP (Maksimal: 2 MB)</small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="keterangan" class="fw-bold">Keterangan / Catatan Objek Wisata (Opsional)</label>
                        <textarea name="keterangan" class="form-control" id="keterangan" rows="3"
                            placeholder="Informasi jam operasional atau fasilitas pendukung...">{{ old('keterangan', $destinasi->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-save me-1"></i> Perbarui Destinasi
                </button>
                <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
