@extends('layouts.admin')

@section('content')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .smart-sticky-active {
            position: fixed !important;
            top: 85px !important;
            z-index: 99 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            transition: top 0.1s ease-out;
        }

        .selected-pills-container {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: flex-start !important;
            align-items: center !important;
            gap: 0.5rem !important;
            margin-top: 0.5rem !important;
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
            margin-left: 0.25rem !important;
        }
    </style>

    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-4 gap-4">
            <div>
                <h4 class="card-title mb-0">Edit Proposal: {{ $proposal->kode_proposal }}</h4>
                <p class="text-muted mb-0">Perbarui status atau rincian data proposal penawaran perjalanan.</p>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.proposal.index') }}" class="btn btn-outline-secondary btn-round">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Terjadi Kesalahan Input:</h6>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.proposal.update', $proposal->uuid) }}" method="POST" id="form-proposal">
            @csrf
            @method('PUT')

            <div class="row align-items-start">
                @if (isset($destinasiList) && isset($armadaList))
                    <div class="col-lg-8">
                        <div class="col">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="nama_klien" class="form-label fw-bold">Nama Klien / Instansi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nama_klien" class="form-control" id="nama_klien"
                                        value="{{ old('nama_klien', $proposal->nama_klien) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-bold">Status Proposal <span
                                            class="text-danger">*</span></label>
                                    <select name="status" class="form-select fw-bold" id="status" required>
                                        <option value="draft"
                                            {{ old('status', $proposal->status) == 'draft' ? 'selected' : '' }}>Draft
                                            (Konsep)</option>
                                        <option value="sent"
                                            {{ old('status', $proposal->status) == 'sent' ? 'selected' : '' }}>Sent
                                            (Terkirim)</option>
                                        <option value="approved"
                                            {{ old('status', $proposal->status) == 'approved' ? 'selected' : '' }}>Approved
                                            (Disetujui)</option>
                                        <option value="rejected"
                                            {{ old('status', $proposal->status) == 'rejected' ? 'selected' : '' }}>Rejected
                                            (Ditolak)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="tanggal_proposal" class="form-label fw-bold">Tanggal Proposal <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_proposal" class="form-control" id="tanggal_proposal"
                                        value="{{ old('tanggal_proposal', $proposal->tanggal_proposal) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="durasi" class="form-label fw-bold">Durasi Tour <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="durasi" class="form-control" id="durasi"
                                        value="{{ old('durasi', $proposal->durasi ?? '1 Hari') }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="jumlah_peserta" class="form-label fw-bold">Peserta (Pax) <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="jumlah_peserta" class="form-control calc-trigger"
                                            id="jumlah_peserta" min="1"
                                            value="{{ old('jumlah_peserta', $proposal->jumlah_peserta) }}" required>
                                        <span class="input-group-text">Pax</span>
                                    </div>
                                </div>
                            </div>

                            @php $selectedArmada = $selectedArmada ?? $proposal->proposalArmada->first(); @endphp
                            <div class="mb-3">
                                <label for="armada_id" class="form-label fw-bold">Pilih Armada Bus <span
                                        class="text-danger">*</span></label>
                                <select name="armada_id" class="form-select calc-trigger" id="armada_id" required>
                                    <option value="">-- Pilih Unit Armada --</option>
                                    @foreach ($armadaList as $arm)
                                        <option value="{{ $arm->id }}" data-sewa="{{ $arm->sewa_inc_solar }}"
                                            {{ old('armada_id', $selectedArmada->armada_id ?? '') == $arm->id ? 'selected' : '' }}>
                                            {{ $arm->jenis_armada }} ({{ $arm->kapasitas }} Seat) — Rp
                                            {{ number_format($arm->sewa_inc_solar, 0, ',', '.') }}/hari
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_unit_armada" class="form-label fw-bold">Jumlah Unit <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="jumlah_unit_armada" class="form-control calc-trigger"
                                        id="jumlah_unit_armada" min="1"
                                        value="{{ old('jumlah_unit_armada', $selectedArmada->jumlah_unit ?? 1) }}"
                                        required>
                                    <span class="input-group-text">Unit</span>
                                </div>
                            </div>

                            <!-- RUTE TOL -->
                            @php $selectedTol = $selectedTol ?? $proposal->proposalTol->pluck('rute_tol_id')->toArray(); @endphp
                            <div class="mb-3">
                                <label class="form-label fw-bold mb-1">
                                    Pilih Rute Jalur Tol <span class="text-muted fw-normal">(Opsional)</span>
                                </label>
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
                                                <div class="dropdown-item rounded d-flex align-items-center justify-content-between py-2 px-2 select-option"
                                                    data-name="{{ strtolower($tol->nama_rute) }}">
                                                    <div
                                                        class="d-flex align-items-center gap-2 cursor-pointer flex-grow-1">
                                                        <input type="checkbox"
                                                            class="form-check-input m-0 calc-trigger tol-checkbox"
                                                            name="rute_tol_ids[]" value="{{ $tol->id }}"
                                                            data-tarif="{{ $tol->tarif_total }}"
                                                            id="tol_{{ $tol->id }}"
                                                            {{ in_array($tol->id, old('rute_tol_ids', $selectedTol)) ? 'checked' : '' }}>
                                                        <label for="tol_{{ $tol->id }}"
                                                            class="text-dark option-label-text mb-0 cursor-pointer">{{ $tol->nama_rute }}</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 ms-2">
                                                        @php $savedType = $selectedTolType[$tol->id] ?? 'pp'; @endphp
                                                        <select name="rute_tol_type[{{ $tol->id }}]"
                                                            class="form-select form-select-sm py-1 ps-2 pe-4 tol-type-select calc-trigger"
                                                            style="font-size: 11px; height: 28px; min-width: 130px; width: auto;">
                                                            <option value="pp"
                                                                {{ old("rute_tol_type.{$tol->id}", $savedType) == 'pp' ? 'selected' : '' }}>
                                                                Pulang Pergi (PP)</option>
                                                            <option value="oneway"
                                                                {{ old("rute_tol_type.{$tol->id}", $savedType) == 'oneway' ? 'selected' : '' }}>
                                                                Sekali Jalan</option>
                                                        </select>
                                                        <small class="text-success fw-bold text-nowrap"
                                                            style="min-width: 80px; text-align: right;">
                                                            Rp {{ number_format($tol->tarif_total, 0, ',', '.') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="emptyRuteTol" class="text-center text-muted py-3 d-none"><small>Rute tol
                                                tidak ditemukan.</small></div>
                                    </div>
                                </div>
                                <div id="selectedRuteTol" class="selected-pills-container"></div>
                            </div>

                            <!-- DESTINASI -->
                            @php $selectedDestinasi = $selectedDestinasi ?? $proposal->proposalDestinasi->pluck('destinasi_id')->toArray(); @endphp
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
                                                            {{ in_array($dest->id, old('destinasi_ids', $selectedDestinasi)) ? 'checked' : '' }}>
                                                        <span
                                                            class="text-dark option-label-text">{{ $dest->nama_destinasi }}</span>
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

                            <!-- PREFILL STANDAR BIAYA OPERASIONAL -->
                            @php
                                $compFixed = $proposal->proposalBiayaKomponen
                                    ->where('kategori', 'fixed')
                                    ->pluck('nominal_satuan', 'nama_komponen')
                                    ->toArray();
                                $compVar = $proposal->proposalBiayaKomponen
                                    ->where('kategori', 'variable')
                                    ->pluck('nominal_satuan', 'nama_komponen')
                                    ->toArray();
                            @endphp


                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Tip Supir & Co-Driver</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_tip_driver"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_tip_driver', (int) ($compFixed['Tip Supir & Co-Driver'] ?? 400000)) }}"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Tour Leader / Guide</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_tour_leader"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_tour_leader', (int) ($compFixed['Tour Leader / Guide'] ?? 400000)) }}"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">BBM & Parkir</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_bbm_parkir"
                                            class="form-control calc-trigger fixed-nominal"
                                            value="{{ old('biaya_bbm_parkir', (int) ($compFixed['BBM, Operasional & Parkir'] ?? 700000)) }}"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Snack & Air Mineral</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_snack"
                                            class="form-control calc-trigger var-nominal"
                                            value="{{ old('biaya_snack', (int) ($compVar['Snack & Air Mineral'] ?? 10000)) }}"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Asuransi Perjalanan</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_asuransi"
                                            class="form-control calc-trigger var-nominal"
                                            value="{{ old('biaya_asuransi', (int) ($compVar['Asuransi Perjalanan'] ?? 5000)) }}"
                                            placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1 fw-bold">Konsumsi / Makan</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="biaya_makan"
                                            class="form-control calc-trigger var-nominal"
                                            value="{{ old('biaya_makan', (int) ($compVar['Konsumsi / Makan'] ?? 75000)) }}"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <!-- DYNAMIC ADDITIONAL COSTS EDIT -->
                            <div class="mb-3">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold mb-0">Biaya Tambahan Lainnya Per Paket
                                            (Fixed)</label>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-round"
                                            id="add-fixed-btn">
                                            <i class="fas fa-plus me-1"></i>Tambah Baris
                                        </button>
                                    </div>
                                    <div id="wrapper-fixed-cost">
                                        @php
                                            $stdFixedKeys = [
                                                'Tip Supir & Co-Driver',
                                                'Tour Leader / Guide',
                                                'BBM, Operasional & Parkir',
                                            ];
                                            $customFixedList = $proposal->proposalBiayaKomponen
                                                ->where('kategori', 'fixed')
                                                ->reject(fn($i) => in_array($i->nama_komponen, $stdFixedKeys))
                                                ->values();
                                        @endphp
                                        @foreach ($customFixedList as $index => $item)
                                            <div class="row g-2 mb-2 fixed-item align-items-center">
                                                <div class="col-md-6">
                                                    <input type="text"
                                                        name="komponen_fixed[{{ $index }}][nama]"
                                                        class="form-control form-control-sm"
                                                        value="{{ $item->nama_komponen }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number"
                                                            name="komponen_fixed[{{ $index }}][nominal]"
                                                            class="form-control calc-trigger fixed-nominal"
                                                            value="{{ (int) $item->nominal_satuan }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 text-end">
                                                    <button type="button"
                                                        class="btn btn-xs btn-link text-danger remove-row-btn"><i
                                                            class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <hr>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold mb-0">Biaya Tambahan Lainnya Per Orang
                                            (Variable)</label>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-round"
                                            id="add-var-btn">
                                            <i class="fas fa-plus me-1"></i>Tambah Baris
                                        </button>
                                    </div>
                                    <div id="wrapper-var-cost">
                                        @php
                                            $stdVarKeys = [
                                                'Snack & Air Mineral',
                                                'Asuransi Perjalanan',
                                                'Konsumsi / Makan',
                                            ];
                                            $customVarList = $proposal->proposalBiayaKomponen
                                                ->where('kategori', 'variable')
                                                ->reject(fn($i) => in_array($i->nama_komponen, $stdVarKeys))
                                                ->values();
                                        @endphp
                                        @foreach ($customVarList as $index => $item)
                                            <div class="row g-2 mb-2 var-item align-items-center">
                                                <div class="col-md-6">
                                                    <input type="text" name="komponen_var[{{ $index }}][nama]"
                                                        class="form-control form-control-sm"
                                                        value="{{ $item->nama_komponen }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number"
                                                            name="komponen_var[{{ $index }}][nominal]"
                                                            class="form-control calc-trigger var-nominal"
                                                            value="{{ (int) $item->nominal_satuan }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 text-end">
                                                    <button type="button"
                                                        class="btn btn-xs btn-link text-danger remove-row-btn"><i
                                                            class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="fasilitas" class="form-label fw-bold">Fasilitas Paket Perjalanan</label>
                                <textarea name="fasilitas" id="fasilitas" class="form-control" rows="4">{{ old('fasilitas', $proposal->fasilitas) }}</textarea>
                            </div>
                        </div>

                        <!-- Profit Margin -->
                        <div class="card mb-4 border-success">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <h6 class="fw-bold text-success mb-1"><i class="fas fa-coins me-3"></i>Target
                                            Profit Margin Per Pax</h6>
                                        <p class="text-muted small mb-0">Nominal keuntungan bersih yang diambil per
                                            peserta.</p>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white fw-bold">Rp</span>
                                            <input type="number" name="margin_per_pax"
                                                class="form-control form-control-lg fw-bold text-success calc-trigger"
                                                id="margin_per_pax" min="0"
                                                value="{{ old('margin_per_pax', (int) $proposal->margin_per_pax) }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STICKY CALC CARD EDIT -->
                    <div class="col-lg-4" id="sticky-column-parent">
                        <div class="card border-secondary shadow" id="sticky-calculator">
                            <div class="card-header bg-secondary text-white rounded-top">
                                <div class="card-title text-white mb-0 d-flex align-items-center justify-content-between"
                                    style="font-size: 15px;">
                                    <span><i class="fas fa-calculator me-2"></i>Simulasi Penawaran</span>
                                    <span class="badge bg-white text-secondary fw-bold" style="font-size: 9px;">LIVE
                                        EDIT</span>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-3 pb-2 border-bottom">
                                    <span class="text-muted small">Total Peserta:</span>
                                    <h6 class="fw-bold text-dark mb-0" id="disp-total-peserta">
                                        {{ $proposal->jumlah_peserta }} Pax</h6>
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
                                    <h6 class="fw-bold text-success mb-0" id="disp-margin-pax">Rp 0</h6>
                                </div>

                                <div class="p-3 bg-light rounded border mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted small fw-bold text-uppercase"
                                            style="font-size: 11px;">Harga / Pax:</span>
                                        <h3 class="fw-extrabold text-secondary mb-0" id="disp-final-pax">Rp 0</h3>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small" style="font-size: 11px;">Total Omset:</span>
                                        <h5 class="fw-bold text-success mb-0" id="disp-grand-total">Rp 0</h5>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-secondary btn-round w-100 font-weight-bold shadow-sm mb-2">
                                    <i class="fas fa-save me-1"></i> Perbarui Proposal
                                </button>
                                <a href="{{ route('admin.proposal.show', $proposal->uuid) }}"
                                    class="btn btn-outline-secondary btn-round w-100">Batal</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>

    @if (isset($destinasiList) && isset($armadaList))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let fixedIndex = {{ count($customFixedList ?? []) ?: 0 }};
                let varIndex = {{ count($customVarList ?? []) ?: 0 }};

                const card = document.getElementById('sticky-calculator');
                const parent = document.getElementById('sticky-column-parent');

                function updateStickyPosition() {
                    if (window.innerWidth < 992) {
                        card.classList.remove('smart-sticky-active');
                        card.style.width = '100%';
                        return;
                    }
                    const parentRect = parent.getBoundingClientRect();
                    if (parentRect.top <= 85) {
                        if (!card.classList.contains('smart-sticky-active')) {
                            card.style.width = parentRect.width + 'px';
                            card.classList.add('smart-sticky-active');
                        }
                    } else {
                        card.classList.remove('smart-sticky-active');
                        card.style.width = '100%';
                    }
                }

                window.addEventListener('scroll', updateStickyPosition);
                window.addEventListener('resize', updateStickyPosition);

                function formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0
                    }).format(number);
                }

                function recalculate() {
                    let peserta = parseInt(document.getElementById('jumlah_peserta').value) || 1;
                    let armadaSelect = document.getElementById('armada_id');
                    let selectedOption = armadaSelect.options[armadaSelect.selectedIndex];
                    let sewaArmada = parseFloat(selectedOption ? selectedOption.getAttribute('data-sewa') || 0 : 0);
                    let unitArmada = parseInt(document.getElementById('jumlah_unit_armada').value) || 1;

                    let totalArmada = sewaArmada * unitArmada;

                    let totalTol = 0;
                    document.querySelectorAll('input[name="rute_tol_ids[]"]:checked').forEach(cb => {
                        let tarifBase = parseFloat(cb.getAttribute('data-tarif') || 0);
                        let optionRow = cb.closest('.select-option');
                        let typeSelect = optionRow.querySelector('.tol-type-select');
                        let multiplier = (typeSelect && typeSelect.value === 'pp') ? 2 : 1;
                        totalTol += (tarifBase * multiplier);
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

                    let totalVarLainnya = 0;
                    document.querySelectorAll('.var-nominal').forEach(inp => {
                        totalVarLainnya += parseFloat(inp.value || 0);
                    });

                    let totalBiayaOperasional = totalFixedGroup + ((totalHTM + totalVarLainnya) * peserta);
                    let modalPerPax = totalBiayaOperasional / peserta;

                    let marginPerPax = parseFloat(document.getElementById('margin_per_pax').value || 0);
                    let finalPerPax = modalPerPax + marginPerPax;
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
                            <input type="text" name="komponen_fixed[${fixedIndex}][nama]" class="form-control form-control-sm" placeholder="Nama Biaya Tambahan">
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
                            <input type="text" name="komponen_var[${varIndex}][nama]" class="form-control form-control-sm" placeholder="Nama Biaya Tambahan Per Pax">
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
                            let tagType = '';
                            const typeSelect = option.querySelector('.tol-type-select');
                            if (typeSelect) {
                                tagType = (typeSelect.value === 'pp') ?
                                    ' <span class="badge bg-secondary p-1">PP</span>' :
                                    ' <span class="badge bg-light text-dark border p-1">1x</span>';
                            }
                            const pill = document.createElement('span');
                            pill.className = 'selected-pill-item';
                            pill.innerHTML =
                                `<span>${name}${tagType}</span><button type="button" class="btn-close" data-id="${checkbox.id}"></button>`;
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
                    optionsContainer.querySelectorAll('.tol-type-select').forEach(function(select) {
                        select.addEventListener('change', function() {
                            updateSelection();
                            recalculate();
                        });
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
                updateStickyPosition();
            });
        </script>
    @endif
@endsection
