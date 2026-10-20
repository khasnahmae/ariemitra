@extends('layouts.admin')

@section('content')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .select-option:hover {
            background-color: #f8f9fa;
        }
    </style>

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

                    <!-- CUSTOM DROPDOWN WITH LIVE SEARCH BAR EDIT -->
                    @php $currentLokasi = old('lokasi',$destinasi->lokasi); @endphp
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">Lokasi / Kota / Kabupaten <span class="text-danger">*</span></label>
                        <input type="hidden" name="lokasi" id="lokasi_value" value="{{ $currentLokasi }}" required>

                        <div class="dropdown w-100">
                            <button type="button"
                                class="form-select text-start d-flex align-items-center justify-content-between"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" id="lokasiDropdown">
                                <span id="lokasiPlaceholder"
                                    class="{{ $currentLokasi ? 'text-dark fw-bold' : 'text-muted' }}">
                                    {{ $currentLokasi ? $currentLokasi : 'Pilih kota / kabupaten...' }}
                                </span>
                            </button>

                            <div class="dropdown-menu w-100 p-2 shadow" style="max-height: 350px; overflow-y: auto;">
                                <!-- Live Search Input Bar -->
                                <div class="mb-2 position-relative">
                                    <i
                                        class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" class="form-control ps-5" id="searchLokasi"
                                        placeholder="Cari lokasi/kota..." autocomplete="off">
                                </div>

                                <!-- Dynamic Location List -->
                                <div id="lokasiOptions"></div>

                                <div id="emptyLokasi" class="text-center text-muted py-3 d-none">
                                    <small>Lokasi tidak ditemukan.</small>
                                </div>
                            </div>
                        </div>
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
                                @if (Str::startsWith($destinasi->foto, 'http'))
                                    <img src="{{ $destinasi->foto }}" alt="{{ $destinasi->nama_destinasi }}"
                                        class="img-thumbnail rounded shadow-sm"
                                        style="max-height: 180px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/' . $destinasi->foto) }}"
                                        alt="{{ $destinasi->nama_destinasi }}" class="img-thumbnail rounded shadow-sm"
                                        style="max-height: 180px; object-fit: cover;">
                                @endif
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

    <!-- Script Custom Dropdown dengan Search Bar -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const daftarKotaKab = [
                "Pemalang", "Batang", "Pekalongan", "Kota Pekalongan", "Tegal", "Kota Tegal", "Brebes",
                "Kendal",
                "Semarang", "Kota Semarang", "Salatiga", "Demak", "Kudus", "Jepara", "Pati", "Rembang", "Blora",
                "Grobogan",
                "Boyolali", "Klaten", "Sukoharjo", "Wonogiri", "Karanganyar", "Sragen", "Surakarta", "Magelang",
                "Kota Magelang",
                "Wonosobo", "Temanggung", "Purworejo", "Kebumen", "Banjarnegara", "Purbalingga", "Banyumas",
                "Cilacap",
                "Yogyakarta", "Sleman", "Bantul", "Gunungkidul", "Kulon Progo",
                "Jakarta Pusat", "Jakarta Utara", "Jakarta Barat", "Jakarta Selatan", "Jakarta Timur",
                "Kepulauan Seribu",
                "Tangerang", "Kota Tangerang", "Tangerang Selatan", "Cilegon", "Serang", "Kota Serang", "Lebak",
                "Pandeglang",
                "Bandung", "Kota Bandung", "Bandung Barat", "Sumedang", "Cimahi", "Garut", "Tasikmalaya",
                "Kota Tasikmalaya",
                "Ciamis", "Kuningan", "Cirebon", "Kota Cirebon", "Majalengka", "Indramayu", "Subang",
                "Purwakarta",
                "Karawang", "Bekasi", "Kota Bekasi", "Bogor", "Kota Bogor", "Depok", "Sukabumi",
                "Kota Sukabumi", "Cianjur", "Pangandaran",
                "Surabaya", "Malang", "Kota Malang", "Batu", "Sidoarjo", "Pasuruan", "Kota Pasuruan",
                "Probolinggo", "Kota Probolinggo",
                "Jember", "Banyuwangi", "Lumajang", "Bondowoso", "Situbondo", "Mojokerto", "Kota Mojokerto",
                "Gresik", "Lamongan",
                "Tuban", "Boonegoro", "Ngawi", "Magetan", "Madiun", "Kota Madiun", "Nganjuk", "Kediri",
                "Kota Kediri",
                "Blitar", "Kota Blitar", "Tulungagung", "Trenggalek", "Ponorogo", "Pacitan", "Bangkalan",
                "Sampang", "Pamekasan", "Sumenep",
                "Denpasar", "Badung", "Gianyar", "Tabanan", "Buleleng", "Klungkung", "Karangasem", "Jembrana",
                "Bangli",
                "Mataram", "Bima", "Kota Bima", "Lombok Barat", "Lombok Tengah", "Lombok Timur", "Lombok Utara",
                "Sumbawa", "Sumbawa Barat",
                "Kupang", "Kota Kupang", "Manggarai", "Manggarai Barat (Labuan Bajo)", "Ende", "Sikka",
                "Banda Aceh", "Langsa", "Lhokseumawe", "Sabang", "Subulussalam",
                "Medan", "Binjai", "Gunungsitoli", "Padangsidimpuan", "Pematangsiantar", "Sibolga",
                "Tanjungbalai", "Tebing Tinggi", "Deli Serdang",
                "Padang", "Bukittinggi", "Padang Panjang", "Pariaman", "Payakumbuh", "Sawahlunto", "Solok",
                "Kota Solok", "Pasaman",
                "Pekanbaru", "Dumai", "Kampar", "Jambi", "Sungaipenuh", "Palembang", "Lubuklinggau",
                "Pagar Alam", "Prabumulih",
                "Bengkulu", "Bandar Lampung", "Metro", "Lampung Selatan", "Pangkalpinang", "Batam",
                "Tanjungpinang", "Bintan",
                "Pontianak", "Singkawang", "Banjarbaru", "Banjarmasin", "Palangkaraya", "Balikpapan", "Bontang",
                "Samarinda", "Kutai Kartanegara", "Nusantara (IKN)", "Tarakan",
                "Gorontalo", "Makassar", "Palopo", "Parepare", "Palu", "Baubau", "Kendari", "Manado", "Bitung",
                "Kotamobagu", "Tomohon",
                "Ambon", "Tual", "Ternate", "Tidore Kepulauan", "Jayapura", "Kota Jayapura", "Sorong",
                "Kota Sorong", "Manokwari", "Mimika"
            ];

            const searchInput = document.getElementById('searchLokasi');
            const optionsContainer = document.getElementById('lokasiOptions');
            const hiddenInput = document.getElementById('lokasi_value');
            const placeholder = document.getElementById('lokasiPlaceholder');
            const emptyState = document.getElementById('emptyLokasi');

            // Populate Item Daftar Kota
            daftarKotaKab.forEach(function(namaKota) {
                const item = document.createElement('div');
                item.className = 'dropdown-item rounded py-2 px-3 cursor-pointer select-option';
                item.dataset.name = namaKota.toLowerCase();
                item.innerHTML = `<span class="text-dark option-label-text">${namaKota}</span>`;

                item.addEventListener('click', function() {
                    hiddenInput.value = namaKota;
                    placeholder.textContent = namaKota;
                    placeholder.className = 'text-dark fw-bold';

                    // Close Bootstrap Dropdown
                    const dropdownBtn = document.getElementById('lokasiDropdown');
                    const bsDropdown = bootstrap.Dropdown.getInstance(dropdownBtn) || new bootstrap
                        .Dropdown(dropdownBtn);
                    bsDropdown.hide();
                });

                optionsContainer.appendChild(item);
            });

            // Live Search Filter Listener
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();
                const options = optionsContainer.querySelectorAll('.select-option');
                let visibleCount = 0;

                options.forEach(function(option) {
                    const name = option.dataset.name;
                    if (name.includes(keyword)) {
                        option.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        option.classList.add('d-none');
                    }
                });

                emptyState.classList.toggle('d-none', visibleCount > 0);
            });
        });
    </script>
@endsection
