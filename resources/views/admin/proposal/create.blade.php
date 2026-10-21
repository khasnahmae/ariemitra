@extends('layouts.admin')

@section('content')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .smart-sticky-active {
            position: fixed !important;
            top: 90px !important;
            z-index: 1020 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            transition: width 0.1s ease, top 0.1s ease;
        }

        .selected-pills-container {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0.5rem !important;
            margin-top: 0.5rem !important;
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
        }
    </style>

    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-4 gap-4">
            <div>
                <h4 class="card-title mb-0">Buat Proposal Paket Wisata</h4>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.proposal.index') }}" class="btn btn-outline-secondary btn-round">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.proposal.store') }}" method="POST" id="form-proposal">
            @csrf

            <div class="row align-items-start">
                <div class="col-lg-8">
                    <!-- BLOCK KATEGORI PAKET WISATA -->
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3">Kategori Paket Wisata</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="paket_wisata_id" class="form-label fw-bold">Pilih Kategori Paket Wisata
                                        <span class="text-danger">*</span></label>
                                    <select name="paket_wisata_id" id="paket_wisata_id" class="form-select" required>
                                        <option value="">-- Pilih Kategori Paket Wisata --</option>
                                        @foreach ($paketList as $pkt)
                                            <option value="{{ $pkt->id }}"
                                                {{ old('paket_wisata_id') == $pkt->id ? 'selected' : '' }}>
                                                Paket {{ $pkt->nomor_paket }} - {{ $pkt->nama_paket }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted mt-1 d-block">Nama Sub Paket (contoh: 1A, 1B, 2A) akan
                                        ter-generate otomatis saat proposal disimpan.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BLOCK DURASI & PENGINAPAN -->
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3">Durasi Perjalanan</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="is_menginap" class="form-label fw-bold">Opsi Menginap <span
                                            class="text-danger">*</span></label>
                                    <select name="is_menginap" id="is_menginap" class="form-select calc-trigger" required>
                                        <option value="tidak" {{ old('is_menginap') == 'tidak' ? 'selected' : '' }}>Tidak
                                            Menginap (1 Hari)</option>
                                        <option value="ya" {{ old('is_menginap') == 'ya' ? 'selected' : '' }}>Menginap
                                            (N-Hari N-Malam)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="jumlah_hari" class="form-label fw-bold">Jumlah Hari <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_hari" id="jumlah_hari"
                                        class="form-control calc-trigger" min="1" value="{{ old('jumlah_hari', 1) }}"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="jumlah_malam" class="form-label fw-bold">Jumlah Malam <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_malam" id="jumlah_malam"
                                        class="form-control calc-trigger" min="0"
                                        value="{{ old('jumlah_malam', 0) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOCK PESERTA & ARMADA -->
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3">Kebutuhan Peserta & Unit Bus</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="jumlah_peserta" class="form-label fw-bold">Jumlah Peserta (Pax) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_peserta" id="jumlah_peserta"
                                        class="form-control calc-trigger" min="1"
                                        value="{{ old('jumlah_peserta', 50) }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="armada_id" class="form-label fw-bold">Pilih Armada Bus <span
                                            class="text-danger">*</span></label>
                                    <select name="armada_id" id="armada_id" class="form-select calc-trigger" required>
                                        <option value="">-- Pilih Unit Armada --</option>
                                        @foreach ($armadaList as $arm)
                                            <option value="{{ $arm->id }}" data-sewa="{{ $arm->harga_sewa }}"
                                                data-seat="{{ $arm->kapasitas }}"
                                                data-solar="{{ $arm->is_include_solar }}"
                                                {{ old('armada_id') == $arm->id ? 'selected' : '' }}>
                                                {{ $arm->jenis_armada }} ({{ $arm->kapasitas }} Seat) — Rp
                                                {{ number_format($arm->harga_sewa, 0, ',', '.') }}/hari [Solar:
                                                {{ strtoupper($arm->is_include_solar) }}]
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="jumlah_unit_armada" class="form-label fw-bold">Jumlah Unit <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_unit_armada" id="jumlah_unit_armada"
                                        class="form-control calc-trigger" min="1"
                                        value="{{ old('jumlah_unit_armada', 1) }}" required>
                                </div>

                                <!-- REAL-TIME ERROR CAPACITY BADGE -->
                                <div class="col-12 d-none" id="capacity-error-wrapper">
                                    <div class="alert alert-danger py-2 mb-0 d-flex align-items-center gap-2">
                                        <i class="fas fa-exclamation-circle fa-lg"></i>
                                        <span id="capacity-error-msg" class="fw-bold small"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-body">
                            <!-- RUTE TOL -->
                            <div class="mb-3">
                                <label class="form-label fw-bold mb-1">Pilih Rute Jalur Tol PP</label>
                                <div class="dropdown w-100">
                                    <button type="button"
                                        class="form-select text-start d-flex align-items-center justify-content-between"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" id="ruteTolDropdown">
                                        <span id="ruteTolPlaceholder" class="text-muted">Pilih rute tol...</span>
                                        <span id="ruteTolCount" class="badge bg-success d-none">0</span>
                                    </button>
                                    <div class="dropdown-menu w-100 p-2 shadow"
                                        style="max-height: 380px; overflow-y: auto;">
                                        <div class="mb-2 position-relative">
                                            <i
                                                class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                            <input type="text" class="form-control ps-5" id="searchRuteTol"
                                                placeholder="Cari rute tol..." autocomplete="off">
                                        </div>
                                        <div id="ruteTolOptions">
                                            @foreach ($ruteTolList as $tol)
                                                <div class="dropdown-item rounded d-flex align-items-center justify-content-between py-2 px-2 cursor-pointer select-option"
                                                    data-name="{{ strtolower($tol->nama_rute) }}">
                                                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                                                        <input type="checkbox"
                                                            class="form-check-input m-0 calc-trigger tol-checkbox"
                                                            name="rute_tol_ids[]" value="{{ $tol->id }}"
                                                            data-tarif="{{ $tol->tarif_total }}"
                                                            id="tol_{{ $tol->id }}"
                                                            {{ is_array(old('rute_tol_ids')) && in_array($tol->id, old('rute_tol_ids')) ? 'checked' : '' }}>
                                                        <label for="tol_{{ $tol->id }}"
                                                            class="text-dark option-label-text mb-0 cursor-pointer">{{ $tol->nama_rute }}</label>
                                                    </div>
                                                    <small class="text-success fw-bold text-nowrap ms-2">Rp
                                                        {{ number_format($tol->tarif_total, 0, ',', '.') }}</small>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="emptyRuteTol" class="text-center text-muted py-3 d-none"><small>Rute tol
                                                tidak
                                                ditemukan.</small></div>
                                    </div>
                                </div>
                                <div id="selectedRuteTol" class="selected-pills-container"></div>
                            </div>

                            <!-- DESTINASI -->
                            <div class="mb-3">
                                <label class="form-label fw-bold mb-1">Pilih Destinasi Wisata <span
                                        class="text-danger">*</span></label>
                                <div class="dropdown w-100">
                                    <button type="button"
                                        class="form-select text-start d-flex align-items-center justify-content-between"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" id="destinasiDropdown">
                                        <span id="destinasiPlaceholder" class="text-muted">Pilih destinasi
                                            wisata...</span>
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
                                                            class="text-dark option-label-text">{{ $dest->nama_destinasi }}
                                                            ({{ $dest->lokasi }})
                                                        </span>
                                                    </div>
                                                    <small class="text-muted text-nowrap ms-2">HTM: <span
                                                            class="text-secondary fw-bold">Rp
                                                            {{ number_format($dest->htm_per_orang, 0, ',', '.') }}</span>/pax</small>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div id="emptyDestinasi" class="text-center text-muted py-3 d-none">
                                            <div class="small">Destinasi tidak ditemukan.</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="selectedDestinasi" class="selected-pills-container"></div>
                            </div>
                        </div>
                    </div>

                    <!-- STANDAR FIXED COSTS FIELD -->
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3">Komponen Biaya Operasional / Kelompok</h6>
                            <div class="col g-2">
                                <div class="col-md-12 mb-3 d-none" id="field-sewa-tambahan-wrapper">
                                    <label class="small text-danger mb-1 fw-bold">Biaya Tambahan Sewa Armada</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_sewa_tambahan" id="biaya_sewa_tambahan"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_sewa_tambahan', 0) }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3 d-none" id="field-bbm-wrapper">
                                    <label class="small text-danger mb-1 fw-bold">Biaya BBM / Solar (Bus Non-Inc)</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_bbm" id="biaya_bbm"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_bbm', 0) }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3 d-none" id="field-penginapan-wrapper">
                                    <label class="small text-danger mb-1 fw-bold">Biaya Penginapan / Hotel</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_penginapan" id="biaya_penginapan"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_penginapan', 0) }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="small text-muted mb-1 fw-bold">Tip Supir & Co-Driver</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_tip_driver"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_tip_driver', 400000) }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="small text-muted mb-1 fw-bold">Tour Leader / Guide</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_tour_leader" id="biaya_tour_leader"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_tour_leader', 400000) }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="small text-muted mb-1 fw-bold">Biaya Parkir & Retribusi</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_parkir"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_parkir', 200000) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STANDAR VARIABLE COSTS FIELD -->
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3">Komponen Biaya Per Pax / Orang</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Biaya Snack</label>
                                    <div class="input-group input-group-sm mb-1">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="harga_snack" id="harga_snack"
                                            class="form-control calc-trigger" value="{{ old('harga_snack', 10000) }}">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="jumlah_snack" id="jumlah_snack"
                                            class="form-control calc-trigger" value="{{ old('jumlah_snack', 1) }}"
                                            min="0">
                                        <span class="input-group-text">x Snack</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Biaya Makan</label>
                                    <div class="input-group input-group-sm mb-1">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="harga_makan" id="harga_makan"
                                            class="form-control calc-trigger" value="{{ old('harga_makan', 35000) }}">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="jumlah_makan" id="jumlah_makan"
                                            class="form-control calc-trigger" value="{{ old('jumlah_makan', 2) }}"
                                            min="0">
                                        <span class="input-group-text">x Makan</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Asuransi Perjalanan</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_asuransi" id="biaya_asuransi"
                                            class="form-control calc-trigger var-nominal"
                                            value="{{ old('biaya_asuransi', 5000) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 border">
                        <div class="card-body">
                            <!-- DYNAMIC ADDITIONAL COSTS -->
                            <div class="mb-3">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold mb-0">Biaya Tambahan Custom (Kelompok)</label>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-round"
                                            id="add-fixed-btn">
                                            <i class="fas fa-plus me-1"></i>Tambah Baris
                                        </button>
                                    </div>
                                    <div id="wrapper-fixed-cost"></div>
                                </div>

                                <hr>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold mb-0">Biaya Tambahan Custom (Per Pax)</label>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-round"
                                            id="add-var-btn">
                                            <i class="fas fa-plus me-1"></i>Tambah Baris
                                        </button>
                                    </div>
                                    <div id="wrapper-var-cost"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Margin -->
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <h6 class="fw-bold text-success mb-1">Target Profit
                                        Margin Per Pax <span class="text-danger">*</span></h6>
                                    <p class="text-muted small mb-0">Nominal keuntungan bersih yang diambil per peserta.
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-success text-white fw-bold">Rp</span>
                                        <input type="number" name="margin_per_pax" id="margin_per_pax"
                                            class="form-control form-control-lg fw-bold text-success calc-trigger"
                                            min="0" value="{{ old('margin_per_pax', 50000) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PREVIEW HASIL SINKRONISASI FASILITAS -->
                    <div class="mb-3">
                        <label for="fasilitas" class="form-label fw-bold">Fasilitas</label>
                        <textarea name="fasilitas" id="fasilitas" class="form-control bg-light" rows="5" readonly></textarea>
                    </div>

                </div>

                <!-- STICKY SIMULATION CARD CONTAINER -->
                <div class="col-lg-4" id="sticky-column-parent">
                    <div class="card border-secondary shadow" id="sticky-calculator">
                        <div class="card-header bg-secondary text-white rounded-top">
                            <div class="card-title text-white mb-0 d-flex align-items-center justify-content-between"
                                style="font-size: 15px;">
                                <span><i class="fas fa-calculator me-2"></i>Simulasi Penawaran</span>
                                <span class="badge bg-white text-secondary fw-bold" style="font-size: 9px;">LIVE</span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3 pb-2 border-bottom">
                                <span class="text-muted small">Total Peserta:</span>
                                <h6 class="fw-bold text-dark mb-0" id="disp-total-peserta">50 Pax</h6>
                            </div>

                            <div class="mb-3 pb-2 border-bottom">
                                <div class="mb-1">
                                    <span class="text-muted small">Total Biaya Operasional:</span>
                                    <h6 class="fw-bold text-dark mb-0" id="disp-total-biaya">Rp 0</h6>
                                </div>
                                <small class="text-muted font-italic d-block text-start" id="disp-biaya-per-pax"
                                    style="font-size: 11px;">(Modal/Pax: Rp 0)</small>
                            </div>

                            <div class="mb-3 pb-2 border-bottom">
                                <span class="text-muted d-block small">Target Profit Margin / Pax:</span>
                                <h6 class="fw-bold text-success mb-0" id="disp-margin-pax">Rp 50.000</h6>
                            </div>

                            <div class="p-3 bg-light rounded border mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px;">Harga /
                                        Pax:</span>
                                    <h3 class="fw-extrabold text-secondary mb-0" id="disp-final-pax">Rp 0</h3>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small" style="font-size: 11px;">Total Omset:</span>
                                    <h5 class="fw-bold text-success mb-0" id="disp-grand-total">Rp 0</h5>
                                </div>
                            </div>

                            <button type="submit" id="submit-btn"
                                class="btn btn-secondary btn-round w-100 font-weight-bold shadow-sm">
                                <i class="fas fa-check-circle me-1"></i> Simpan & Buat Proposal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- SCRIPT REAL-TIME TRIGGERS & DYNAMIC STICKY LISTENER -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let fixedIndex = 0;
            let varIndex = 0;

            const isMenginapSelect = document.getElementById('is_menginap');
            const jumlahHariInput = document.getElementById('jumlah_hari');
            const jumlahMalamInput = document.getElementById('jumlah_malam');
            const armadaSelect = document.getElementById('armada_id');
            const submitBtn = document.getElementById('submit-btn');

            // --- JAVASCRIPT REAL-TIME DYNAMIC STICKY LOGIC ---
            const stickyCard = document.getElementById('sticky-calculator');
            const stickyParent = document.getElementById('sticky-column-parent');

            function handleStickyPosition() {
                if (window.innerWidth < 992) {
                    stickyCard.classList.remove('smart-sticky-active');
                    stickyCard.style.width = '100%';
                    return;
                }

                const parentRect = stickyParent.getBoundingClientRect();

                // Ketika posisi atas parent sudah melewati offset top 90px
                if (parentRect.top <= 90) {
                    if (!stickyCard.classList.contains('smart-sticky-active')) {
                        stickyCard.style.width = parentRect.width + 'px';
                        stickyCard.classList.add('smart-sticky-active');
                    } else {
                        stickyCard.style.width = parentRect.width + 'px';
                    }
                } else {
                    stickyCard.classList.remove('smart-sticky-active');
                    stickyCard.style.width = '100%';
                }
            }

            // Dengarkan scroll pada window serta pembungkus internal elemen admin
            window.addEventListener('scroll', handleStickyPosition, {
                passive: true
            });
            window.addEventListener('resize', handleStickyPosition);

            // Mencari kontainer internal template jika scroll terjadi di dalam elemen bertipe overflow
            let scrollableParent = stickyParent.parentElement;
            while (scrollableParent && scrollableParent !== document.body) {
                const computedStyle = window.getComputedStyle(scrollableParent);
                if (computedStyle.overflowY === 'auto' || computedStyle.overflowY === 'scroll') {
                    scrollableParent.addEventListener('scroll', handleStickyPosition, {
                        passive: true
                    });
                }
                scrollableParent = scrollableParent.parentElement;
            }

            // --- KALKULASI & REALTIME TRIGGERS ---
            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(number);
            }

            function handleMenginapLogic() {
                const isMenginap = isMenginapSelect.value === 'ya';
                const fieldPenginapan = document.getElementById('field-penginapan-wrapper');
                const fieldSewaTambahan = document.getElementById('field-sewa-tambahan-wrapper');

                if (isMenginap) {
                    fieldPenginapan.classList.remove('d-none');
                    fieldSewaTambahan.classList.remove('d-none');

                    if (parseInt(jumlahHariInput.value) < 2) jumlahHariInput.value = 2;
                    if (parseInt(jumlahMalamInput.value) < 1) jumlahMalamInput.value = 1;
                    jumlahHariInput.min = 2;
                    jumlahMalamInput.min = 1;
                } else {
                    fieldPenginapan.classList.add('d-none');
                    fieldSewaTambahan.classList.add('d-none');
                    document.getElementById('biaya_penginapan').value = 0;
                    document.getElementById('biaya_sewa_tambahan').value = 0;

                    jumlahHariInput.value = 1;
                    jumlahMalamInput.value = 0;
                    jumlahHariInput.min = 1;
                    jumlahMalamInput.min = 0;
                }
            }

            function handleArmadaSolarLogic() {
                const selectedOption = armadaSelect.options[armadaSelect.selectedIndex];
                const isIncludeSolar = selectedOption ? selectedOption.getAttribute('data-solar') : 'ya';
                const fieldBbm = document.getElementById('field-bbm-wrapper');

                if (isIncludeSolar === 'tidak') {
                    fieldBbm.classList.remove('d-none');
                } else {
                    fieldBbm.classList.add('d-none');
                    document.getElementById('biaya_bbm').value = 0;
                }
            }

            function syncFasilitas() {
                let items = ['Dokumentasi', 'P3K'];

                if (armadaSelect.value !== '') {
                    items.unshift('Bus Pariwisata');
                }

                const checkedDestinasi = document.querySelectorAll('input[name="destinasi_ids[]"]:checked');
                if (checkedDestinasi.length > 0) {
                    items.push('Tiket Masuk');
                }

                const tourLeaderVal = parseFloat(document.getElementById('biaya_tour_leader').value) || 0;
                if (tourLeaderVal > 0) {
                    items.push('Tour leader/guide');
                }

                const hargaMakan = parseFloat(document.getElementById('harga_makan').value) || 0;
                const jumlahMakan = parseInt(document.getElementById('jumlah_makan').value) || 0;
                if (hargaMakan > 0 && jumlahMakan > 0) {
                    items.push(`Makan ${jumlahMakan}x`);
                }

                const hargaSnack = parseFloat(document.getElementById('harga_snack').value) || 0;
                const jumlahSnack = parseInt(document.getElementById('jumlah_snack').value) || 0;
                if (hargaSnack > 0 && jumlahSnack > 0) {
                    items.push(`Snack ${jumlahSnack}x`);
                }

                const asuransiVal = parseFloat(document.getElementById('biaya_asuransi').value) || 0;
                if (asuransiVal > 0) {
                    items.push('Asuransi Perjalanan');
                }

                document.getElementById('fasilitas').value = items.join('\n');
            }

            function validateCapacity() {
                const peserta = parseInt(document.getElementById('jumlah_peserta').value) || 0;
                const unit = parseInt(document.getElementById('jumlah_unit_armada').value) || 1;
                const selectedOption = armadaSelect.options[armadaSelect.selectedIndex];
                const seatPerUnit = selectedOption ? parseInt(selectedOption.getAttribute('data-seat') || 0) : 0;

                const totalKapasitas = seatPerUnit * unit;
                const wrapper = document.getElementById('capacity-error-wrapper');
                const msg = document.getElementById('capacity-error-msg');

                if (armadaSelect.value !== '' && peserta > totalKapasitas) {
                    wrapper.classList.remove('d-none');
                    msg.innerText =
                        `Kapasitas tidak mencukupi! Peserta (${peserta} pax) melebihi batas kapasitas total armada (${totalKapasitas} seat dari ${unit} unit).`;
                    submitBtn.disabled = true;
                } else {
                    wrapper.classList.add('d-none');
                    submitBtn.disabled = false;
                }
            }

            function recalculate() {
                handleMenginapLogic();
                handleArmadaSolarLogic();
                validateCapacity();
                syncFasilitas();

                let peserta = parseInt(document.getElementById('jumlah_peserta').value) || 1;
                let selectedOption = armadaSelect.options[armadaSelect.selectedIndex];
                let sewaBase = parseFloat(selectedOption ? selectedOption.getAttribute('data-sewa') || 0 : 0);
                let unit = parseInt(document.getElementById('jumlah_unit_armada').value) || 1;

                let totalArmada = sewaBase * unit;

                let totalTol = 0;
                document.querySelectorAll('input[name="rute_tol_ids[]"]:checked').forEach(cb => {
                    totalTol += parseFloat(cb.getAttribute('data-tarif') || 0);
                });

                let totalFixedLainnya = 0;
                document.querySelectorAll('.fixed-nominal').forEach(inp => {
                    totalFixedLainnya += parseFloat(inp.value || 0);
                });

                let totalFixedGroup = totalArmada + totalTol + totalFixedLainnya;

                let totalHTM = 0;
                document.querySelectorAll('input[name="destinasi_ids[]"]:checked').forEach(cb => {
                    totalHTM += parseFloat(cb.getAttribute('data-htm') || 0);
                });

                let hargaSnack = parseFloat(document.getElementById('harga_snack').value) || 0;
                let jumlahSnack = parseInt(document.getElementById('jumlah_snack').value) || 0;
                let hargaMakan = parseFloat(document.getElementById('harga_makan').value) || 0;
                let jumlahMakan = parseInt(document.getElementById('jumlah_makan').value) || 0;
                let asuransi = parseFloat(document.getElementById('biaya_asuransi').value) || 0;

                let totalVarPerPaxStd = (hargaSnack * jumlahSnack) + (hargaMakan * jumlahMakan) + asuransi;

                let totalVarCustomPerPax = 0;
                document.querySelectorAll('.var-nominal').forEach(inp => {
                    totalVarCustomPerPax += parseFloat(inp.value || 0);
                });

                let totalVarPerPax = totalHTM + totalVarPerPaxStd + totalVarCustomPerPax;

                let totalBiayaOperasional = totalFixedGroup + (totalVarPerPax * peserta);
                let modalPerPax = totalBiayaOperasional / peserta;

                let marginPerPax = parseFloat(document.getElementById('margin_per_pax').value || 0);
                let rawFinalPerPax = modalPerPax + marginPerPax;

                // PEMBULATAN KE ATAS KELIPATAN 5.000 DI JAVASCRIPT
                let finalPerPax = rawFinalPerPax > 0 ? Math.ceil(rawFinalPerPax / 5000) * 5000 : 0;
                let grandTotal = finalPerPax * peserta;

                document.getElementById('disp-total-peserta').innerText = peserta + ' Pax';
                document.getElementById('disp-total-biaya').innerText = formatRupiah(totalBiayaOperasional);
                document.getElementById('disp-biaya-per-pax').innerText = '(Modal/Pax: ' + formatRupiah(
                    modalPerPax) + ')';
                document.getElementById('disp-margin-pax').innerText = formatRupiah(marginPerPax);
                document.getElementById('disp-final-pax').innerText = formatRupiah(finalPerPax);
                document.getElementById('disp-grand-total').innerText = formatRupiah(grandTotal);
            }

            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('calc-trigger')) recalculate();
            });
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('calc-trigger')) recalculate();
            });

            document.getElementById('add-fixed-btn').addEventListener('click', function() {
                let wrapper = document.getElementById('wrapper-fixed-cost');
                let html = `
                <div class="row g-2 mb-2 fixed-item align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="komponen_fixed[${fixedIndex}][nama]" class="form-control form-control-sm" placeholder="Nama Biaya Kelompok">
                    </div>
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="komponen_fixed[${fixedIndex}][nominal]" class="form-control calc-trigger fixed-nominal" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-xs btn-link text-danger remove-row-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`;
                wrapper.insertAdjacentHTML('beforeend', html);
                fixedIndex++;
                recalculate();
            });

            document.getElementById('add-var-btn').addEventListener('click', function() {
                let wrapper = document.getElementById('wrapper-var-cost');
                let html = `
                <div class="row g-2 mb-2 var-item align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="komponen_var[${varIndex}][nama]" class="form-control form-control-sm" placeholder="Nama Biaya Per Pax">
                    </div>
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="komponen_var[${varIndex}][nominal]" class="form-control calc-trigger var-nominal" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-xs btn-link text-danger remove-row-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`;
                wrapper.insertAdjacentHTML('beforeend', html);
                varIndex++;
                recalculate();
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row-btn')) {
                    e.target.closest('.row').remove();
                    recalculate();
                }
            });

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
                    countBadge.textContent = count;
                    countBadge.classList.toggle('d-none', count === 0);

                    if (count === 0) {
                        placeholder.textContent = config.defaultPlaceholderText;
                        placeholder.classList.add('text-muted');
                    } else {
                        placeholder.textContent = `${count} ${config.unitName} dipilih`;
                        placeholder.classList.remove('text-muted');
                    }

                    selectedContainer.innerHTML = '';
                    selected.forEach(function(checkbox) {
                        const option = checkbox.closest('.select-option');
                        const name = option.querySelector('.option-label-text').textContent.trim();
                        const pill = document.createElement('span');
                        pill.className = 'selected-pill-item';
                        pill.innerHTML =
                            `<span>${name}</span><button type="button" class="btn-close" data-id="${checkbox.id}"></button>`;
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
                    emptyState.classList.toggle('d-none', visible > 0);
                });

                optionsContainer.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                    checkbox.addEventListener('change', updateSelection);
                });

                updateSelection();
            }

            setupCustomSelect({
                searchInputId: 'searchRuteTol',
                optionsContainerId: 'ruteTolOptions',
                countBadgeId: 'ruteTolCount',
                placeholderId: 'ruteTolPlaceholder',
                selectedContainerId: 'selectedRuteTol',
                emptyStateId: 'emptyRuteTol',
                defaultPlaceholderText: 'Pilih rute tol...',
                unitName: 'rute'
            });

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

            recalculate();
            handleStickyPosition();
        });
    </script>
@endsection
