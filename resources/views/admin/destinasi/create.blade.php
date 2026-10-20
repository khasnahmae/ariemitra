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
                        <label for="lokasi" class="fw-bold">Lokasi / Kota / Kabupaten <span
                                class="text-danger">*</span></label>
                        <select name="lokasi" id="lokasi" class="form-select select2-kota" required
                            style="width: 100%;">
                            <option value="">-- Ketik / Pilih Kota atau Kabupaten --</option>
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

    <!-- Script Select2 & Lengkap Daftar Kota / Kabupaten Indonesia -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const daftarKotaKab = [
                // Jawa Tengah
                "Pemalang", "Batang", "Pekalongan", "Kota Pekalongan", "Tegal", "Kota Tegal", "Brebes",
                "Kendal",
                "Semarang", "Kota Semarang", "Salatiga", "Demak", "Kudus", "Jepara", "Pati", "Rembang", "Blora",
                "Grobogan",
                "Boyolali", "Klaten", "Sukoharjo", "Wonogiri", "Karanganyar", "Sragen", "Surakarta", "Magelang",
                "Kota Magelang",
                "Wonosobo", "Temanggung", "Purworejo", "Kebumen", "Banjarnegara", "Purbalingga", "Banyumas",
                "Cilacap",

                // DI Yogyakarta
                "Yogyakarta", "Sleman", "Bantul", "Gunungkidul", "Kulon Progo",

                // DKI Jakarta & Banten
                "Jakarta Pusat", "Jakarta Utara", "Jakarta Barat", "Jakarta Selatan", "Jakarta Timur",
                "Kepulauan Seribu",
                "Tangerang", "Kota Tangerang", "Tangerang Selatan", "Cilegon", "Serang", "Kota Serang", "Lebak",
                "Pandeglang",

                // Jawa Barat
                "Bandung", "Kota Bandung", "Bandung Barat", "Sumedang", "Cimahi", "Garut", "Tasikmalaya",
                "Kota Tasikmalaya",
                "Ciamis", "Kuningan", "Cirebon", "Kota Cirebon", "Majalengka", "Indramayu", "Subang",
                "Purwakarta",
                "Karawang", "Bekasi", "Kota Bekasi", "Bogor", "Kota Bogor", "Depok", "Sukabumi",
                "Kota Sukabumi", "Cianjur", "Pangandaran",

                // Jawa Timur
                "Surabaya", "Malang", "Kota Malang", "Batu", "Sidoarjo", "Pasuruan", "Kota Pasuruan",
                "Probolinggo", "Kota Probolinggo",
                "Jember", "Banyuwangi", "Lumajang", "Bondowoso", "Situbondo", "Mojokerto", "Kota Mojokerto",
                "Gresik", "Lamongan",
                "Tuban", "Boonegoro", "Ngawi", "Magetan", "Madiun", "Kota Madiun", "Nganjuk", "Kediri",
                "Kota Kediri",
                "Blitar", "Kota Blitar", "Tulungagung", "Trenggalek", "Ponorogo", "Pacitan", "Bangkalan",
                "Sampang", "Pamekasan", "Sumenep",

                // Bali & Nusa Tenggara
                "Denpasar", "Badung", "Gianyar", "Tabanan", "Buleleng", "Klungkung", "Karangasem", "Jembrana",
                "Bangli",
                "Mataram", "Bima", "Kota Bima", "Lombok Barat", "Lombok Tengah", "Lombok Timur", "Lombok Utara",
                "Sumbawa", "Sumbawa Barat",
                "Kupang", "Kota Kupang", "Manggarai", "Manggarai Barat (Labuan Bajo)", "Ende", "Sikka",

                // Sumatra
                "Banda Aceh", "Langsa", "Lhokseumawe", "Sabang", "Subulussalam",
                "Medan", "Binjai", "Gunungsitoli", "Padangsidimpuan", "Pematangsiantar", "Sibolga",
                "Tanjungbalai", "Tebing Tinggi", "Deli Serdang",
                "Padang", "Bukittinggi", "Padang Panjang", "Pariaman", "Payakumbuh", "Sawahlunto", "Solok",
                "Kota Solok", "Pasaman",
                "Pekanbaru", "Dumai", "Kampar", "Jambi", "Sungaipenuh",
                "Palembang", "Lubuklinggau", "Pagar Alam", "Prabumulih",
                "Bengkulu", "Bandar Lampung", "Metro", "Lampung Selatan",
                "Pangkalpinang", "Batam", "Tanjungpinang", "Bintan",

                // Kalimantan
                "Pontianak", "Singkawang", "Banjarbaru", "Banjarmasin", "Palangkaraya",
                "Balikpapan", "Bontang", "Samarinda", "Kutai Kartanegara", "Nusantara (IKN)", "Tarakan",

                // Sulawesi, Maluku & Papua
                "Gorontalo", "Makassar", "Palopo", "Parepare", "Palu", "Baubau", "Kendari", "Manado", "Bitung",
                "Kotamobagu", "Tomohon",
                "Ambon", "Tual", "Ternate", "Tidore Kepulauan", "Jayapura", "Kota Jayapura", "Sorong",
                "Kota Sorong", "Manokwari", "Mimika"
            ];

            const $selectLokasi = $('#lokasi');

            daftarKotaKab.forEach(function(kota) {
                if ($selectLokasi.find("option[value='" + kota + "']").length === 0) {
                    $selectLokasi.append(new Option(kota, kota, false, false));
                }
            });

            $selectLokasi.select2({
                theme: 'bootstrap-5',
                placeholder: 'Ketik untuk mencari atau menambah kota/kabupaten...',
                allowClear: true,
                tags: true
            });
        });
    </script>
@endsection
