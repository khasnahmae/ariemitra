@extends('layouts.admin')

@section('content')
    <!-- CDN CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

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
                        <select name="lokasi" id="lokasi" class="form-select select2-kota" required>
                            <option value="">-- Ketik / Pilih Kota --</option>
                            @if (old('lokasi'))
                                <option value="{{ old('lokasi') }}" selected>{{ old('lokasi') }}</option>
                            @endif
                        </select>
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

    <!-- Script Select2 & Daftar Kota Indonesia -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Daftar Kota & Kabupaten Utama di Indonesia
            const daftarKota = [
                "Banda Aceh", "Langsa", "Lhokseumawe", "Sabang", "Subulussalam",
                "Medan", "Binjai", "Gunungsitoli", "Padangsidimpuan", "Pematangsiantar", "Sibolga",
                "Tanjungbalai", "Tebing Tinggi",
                "Padang", "Bukittinggi", "Padang Panjang", "Pariaman", "Payakumbuh", "Sawahlunto", "Solok",
                "Pekanbaru", "Dumai", "Jambi", "Sungaipenuh",
                "Palembang", "Lubuklinggau", "Pagar Alam", "Prabumulih",
                "Bengkulu", "Bandar Lampung", "Metro",
                "Pangkalpinang", "Batam", "Tanjungpinang",
                "Jakarta Pusat", "Jakarta Utara", "Jakarta Barat", "Jakarta Selatan", "Jakarta Timur",
                "Kepulauan Seribu",
                "Bandung", "Banjar", "Bekasi", "Bogor", "Cimahi", "Cirebon", "Depok", "Sukabumi", "Tasikmalaya",
                "Magelang", "Pekalongan", "Salatiga", "Semarang", "Surakarta", "Tegal",
                "Yogyakarta", "Sleman", "Bantul", "Gunungkidul", "Kulon Progo",
                "Batu", "Blitar", "Kediri", "Madiun", "Malang", "Mojokerto", "Pasuruan", "Probolinggo",
                "Surabaya",
                "Tangerang", "Tangerang Selatan", "Cilegon", "Serang",
                "Denpasar", "Badung", "Gianyar", "Tabanan", "Buleleng", "Klungkung", "Karangasem", "Jembrana",
                "Bangli",
                "Mataram", "Bima", "Lombok Barat", "Lombok Tengah", "Lombok Timur", "Lombok Utara",
                "Kupang", "Pontianak", "Singkawang", "Banjarbaru", "Banjarmasin", "Palangkaraya",
                "Balikpapan", "Bontang", "Samarinda", "Nusantara (IKN)", "Tarakan",
                "Gorontalo", "Makassar", "Palopo", "Parepare", "Palu", "Baubau", "Kendari", "Manado", "Bitung",
                "Kotamobagu", "Tomohon",
                "Ambon", "Tual", "Ternate", "Tidore Kepulauan", "Jayapura", "Sorong", "Manokwari"
            ];

            const $selectLokasi = $('#lokasi');

            // Tambahkan daftar kota jika belum terisi oleh old value
            daftarKota.forEach(function(kota) {
                if ($selectLokasi.find("option[value='" + kota + "']").length === 0) {
                    $selectLokasi.append(new Option(kota, kota, false, false));
                }
            });

            // Inisialisasi Select2
            $selectLokasi.select2({
                theme: 'bootstrap-5',
                placeholder: 'Ketik nama kota...',
                allowClear: true,
                tags: true // Mengizinkan input kustom jika kota belum ada di daftar
            });
        });
    </script>
@endsection
