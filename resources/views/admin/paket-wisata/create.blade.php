@extends('layouts.admin')

@section('content')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .selected-pills-container {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: flex-start !important;
            align-items: center !important;
            gap: 0.5rem !important;
            margin-top: 0.75rem !important;
            width: 100% !important;
        }

        .selected-pill-item {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
            padding: 0.35rem 0.65rem !important;
            font-size: 0.8125rem !important;
            font-weight: 500 !important;
            background-color: #f8f9fa !important;
            color: #212529 !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 50rem !important;
        }

        .selected-pill-item .btn-close {
            font-size: 0.55rem !important;
            padding: 0.2rem !important;
            margin-left: 0.15rem !important;
            opacity: 0.6;
        }

        .selected-pill-item .btn-close:hover {
            opacity: 1;
        }
    </style>

    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Tambah Paket Wisata Publik</h4>
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

        <form action="{{ route('admin.paket-wisata.store') }}" method="POST">
            @csrf
            <div class="container-fluid">
                <div class="col">
                    <div class="form-group">
                        <label for="nama_paket" class="fw-bold">Nama Paket Wisata Utama <span
                                class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" class="form-control" id="nama_paket"
                            value="{{ old('nama_paket') }}" placeholder="Contoh: Cirebon Modern Tour" required>
                    </div>

                    <!-- INPUT MANUAL TEXT NAMA SUB PAKET -->
                    <div class="form-group">
                        <label for="nama_sub_paket" class="fw-bold">Nama Sub-Paket / Varian <span
                                class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="text" name="nama_sub_paket" class="form-control" id="nama_sub_paket"
                            value="{{ old('nama_sub_paket') }}" placeholder="Contoh: Sub-Paket 1A">
                        <small class="text-muted">Isi jika paket ini berupa varian/sub-paket spesifik.</small>
                    </div>

                    <div class="form-group">
                        <label for="durasi" class="fw-bold">Durasi Tour <span class="text-danger">*</span></label>
                        <input type="text" name="durasi" class="form-control" id="durasi" value="{{ old('durasi') }}"
                            placeholder="Contoh: 1 Hari Tour" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_mulai_from" class="fw-bold">Harga Mulai Dari (Rp/Pax) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="harga_mulai_from" class="form-control" id="harga_mulai_from"
                            min="0" value="{{ old('harga_mulai_from') }}" placeholder="Contoh: 500000" required>
                    </div>

                    <div class="form-group">
                        <label for="fasilitas" class="form-label fw-bold">Fasilitas Paket Perjalanan</label>
                        <textarea name="fasilitas" id="fasilitas" class="form-control" rows="4"
                            placeholder="Masukkan daftar fasilitas (satu fasilitas per baris)">{{ old('fasilitas', "Bus Pariwisata\nTiket Masuk\nTour Leader/Guide\nMakan 2x, Snack 1x\nDokumentasi\nP3K\nAsuransi Perjalanan") }}</textarea>
                        <small class="text-muted">Gunakan baris baru (Enter) untuk memisahkan setiap poin fasilitas.</small>
                    </div>

                    <div class="form-group">
                        <label for="is_active" class="fw-bold">Status Publikasi <span class="text-danger">*</span></label>
                        <select name="is_active" class="form-select" id="is_active" required>
                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif / Tayang di Web
                            </option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Draft / Sembunyikan
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div>
                                <label class="form-label fw-bold mb-1">
                                    Pilih Destinasi Wisata <span class="text-danger">*</span>
                                </label>
                                <div class="text-muted small mb-2">Pilih satu atau beberapa destinasi wisata.</div>

                                <div class="dropdown w-100">
                                    <button type="button"
                                        class="form-select text-start d-flex align-items-center justify-content-between"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                                        id="destinasiDropdown">
                                        <span id="destinasiPlaceholder" class="text-muted">Pilih destinasi wisata...</span>
                                        <span id="destinasiCount" class="badge bg-secondary d-none">0</span>
                                    </button>

                                    <div class="dropdown-menu w-100 p-2 shadow"
                                        style="max-height: 350px; overflow-y: auto;">
                                        <div class="mb-2 position-relative">
                                            <i
                                                class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                            <input type="text" class="form-control ps-5" id="searchDestinasi"
                                                placeholder="Cari destinasi wisata..." autocomplete="off">
                                        </div>

                                        <div id="destinasiOptions">
                                            @foreach ($destinasiList as $dest)
                                                <label
                                                    class="dropdown-item rounded d-flex align-items-center justify-content-between py-2 px-2 cursor-pointer select-option"
                                                    data-name="{{ strtolower($dest->nama_destinasi) }}">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="checkbox" class="form-check-input m-0 calc-trigger"
                                                            name="destinasi_ids[]" value="{{ $dest->id }}"
                                                            id="dest_{{ $dest->id }}"
                                                            data-htm="{{ $dest->htm_per_orang }}"
                                                            {{ is_array(old('destinasi_ids')) && in_array($dest->id, old('destinasi_ids')) ? 'checked' : '' }}>
                                                        <span
                                                            class="text-dark option-label-text">{{ $dest->nama_destinasi }}</span>
                                                    </div>
                                                    <small class="text-muted text-nowrap ms-2">
                                                        HTM: <span class="text-secondary fw-bold">Rp
                                                            {{ number_format($dest->htm_per_orang, 0, ',', '.') }}</span>/pax
                                                    </small>
                                                </label>
                                            @endforeach
                                        </div>

                                        <div id="emptyDestinasi" class="text-center text-muted py-3 d-none">
                                            <i class="fa fa-search mb-1"></i>
                                            <div class="small">Destinasi tidak ditemukan.</div>
                                        </div>
                                    </div>
                                </div>

                                <div id="selectedDestinasi" class="selected-pills-container"></div>

                                @error('destinasi_ids')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-paper-plane me-1"></i> Terbitkan Paket Wisata
                </button>
                <a href="{{ route('admin.paket-wisata.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function setupCustomSelect(config) {
                const searchInput = document.getElementById(config.searchInputId);
                const optionsContainer = document.getElementById(config.optionsContainerId);
                const options = optionsContainer.querySelectorAll('.select-option');
                const countBadge = document.getElementById(config.countBadgeId);
                const placeholder = document.getElementById(config.placeholderId);
                const selectedContainer = document.getElementById(config.selectedContainerId);
                const emptyState = document.getElementById(config.emptyStateId);

                function updateSelection() {
                    const selected = optionsContainer.querySelectorAll('input[type="checkbox"]:checked');
                    const count = selected.length;

                    if (countBadge) {
                        countBadge.textContent = count;
                        countBadge.classList.toggle('d-none', count === 0);
                    }

                    if (placeholder) {
                        if (count === 0) {
                            placeholder.textContent = config.defaultPlaceholderText;
                            placeholder.classList.add('text-muted');
                        } else {
                            placeholder.textContent = `${count} ${config.unitName} dipilih`;
                            placeholder.classList.remove('text-muted');
                        }
                    }

                    if (selectedContainer) {
                        selectedContainer.innerHTML = '';
                        selected.forEach(function(checkbox) {
                            const option = checkbox.closest('.select-option');
                            const name = option.querySelector('.option-label-text').textContent.trim();

                            const pill = document.createElement('span');
                            pill.className = 'selected-pill-item';
                            pill.innerHTML = `
                                <span>${name}</span>
                                <button type="button" class="btn-close" data-id="${checkbox.id}" aria-label="Hapus ${name}"></button>
                            `;
                            selectedContainer.appendChild(pill);
                        });

                        selectedContainer.querySelectorAll('[data-id]').forEach(function(button) {
                            button.addEventListener('click', function() {
                                const checkbox = document.getElementById(button.dataset.id);
                                if (checkbox) {
                                    checkbox.checked = false;
                                    checkbox.dispatchEvent(new Event('change', {
                                        bubbles: true
                                    }));
                                }
                            });
                        });
                    }
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const keyword = this.value.toLowerCase().trim();
                        let visible = 0;

                        options.forEach(function(option) {
                            const name = option.dataset.name;
                            if (name.includes(keyword)) {
                                option.classList.remove('d-none');
                                visible++;
                            } else {
                                option.classList.add('d-none');
                            }
                        });

                        if (emptyState) {
                            emptyState.classList.toggle('d-none', visible > 0);
                        }
                    });
                }

                optionsContainer.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                    checkbox.addEventListener('change', updateSelection);
                });

                updateSelection();
            }

            setupCustomSelect({
                searchInputId: 'searchDestinasi',
                optionsContainerId: 'destinasiOptions',
                countBadgeId: 'destinasiCount',
                placeholderId: 'destinasiPlaceholder',
                selectedContainerId: 'selectedDestinasi',
                emptyStateId: 'emptyDestinasi',
                defaultPlaceholderText: 'Pilih destinasi wisata...',
                unitName: 'destinasi'
            });
        });
    </script>
@endsection
