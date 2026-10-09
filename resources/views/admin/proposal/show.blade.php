@extends('layouts.admin')

@section('content')
    <style>
        /* Class untuk JS Smart Sticky agar kartu menyatu sempurna tanpa overlapping */
        .smart-sticky-active {
            position: fixed !important;
            top: 85px !important;
            z-index: 99 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            transition: top 0.1s ease-out;
        }
    </style>

    <div class="page-inner">
        <!-- Header Page -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pb-4 gap-4">
            <div>
                <h4 class="card-title mb-0">Rincian Proposal: {{ $proposal->kode_proposal }}</h4>
                <p class="text-muted mb-0">Detail rincian komponen biaya, destinasi, dan ringkasan kalkulasi penawaran.</p>
            </div>
            <div class="ms-md-auto py-2 py-md-0 d-flex gap-2">
                <a href="{{ route('admin.proposal.index') }}" class="btn btn-outline-secondary btn-round">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        <div class="row align-items-start">
            <!-- Main Content Rincian (Kiri) -->
            <div class="col-lg-8">
                <!-- Informasi Klien & Pelaksanaan -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div class="card-title text-secondary mb-0">
                            Informasi Proposal & Klien
                        </div>
                        <div>
                            @if ($proposal->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($proposal->status == 'sent')
                                <span class="badge bg-warning text-dark">Sent</span>
                            @elseif($proposal->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <span class="text-muted d-block small">Nama Klien / Instansi:</span>
                                <h5 class="fw-bold text-dark mb-0">{{ $proposal->nama_klien }}</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <span class="text-muted d-block small">Tanggal Proposal:</span>
                                <h6 class="fw-bold text-dark mb-0">
                                    {{ \Carbon\Carbon::parse($proposal->tanggal_proposal)->translatedFormat('d F Y') }}
                                </h6>
                            </div>
                            <div class="col-md-12 mb-3">
                                <span class="text-muted d-block small">Jumlah Peserta:</span>
                                <h6 class="fw-bold text-dark mb-0">{{ $proposal->jumlah_peserta }} Pax</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="text-muted d-block small">Dibuat Oleh:</span>
                                <span class="fw-bold text-secondary">
                                    <i class="fas fa-user me-1"></i>{{ $proposal->user->name ?? 'Admin System' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rincian Transportasi & Tol -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <div class="card-title text-secondary mb-0">
                            Rincian Biaya / Kelompok
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Biaya</th>
                                        <th>Detail</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proposal->proposalArmada as $pa)
                                        <tr>
                                            <td><strong>Armada:</strong> {{ $pa->armada->jenis_armada ?? 'Unit Bus' }}</td>
                                            <td>{{ $pa->jumlah_unit }} Unit (Rp
                                                {{ number_format($pa->sewa_snapshot, 0, ',', '.') }}/unit)</td>
                                            <td class="text-end font-monospace">
                                                Rp {{ number_format($pa->jumlah_unit * $pa->sewa_snapshot, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach ($proposal->proposalTol as $pt)
                                        <tr>
                                            <td><strong>Rute Tol:</strong> {{ $pt->ruteTol->nama_rute ?? 'Jalur Tol' }}
                                            </td>
                                            <td>Toll Tariff</td>
                                            <td class="text-end font-monospace">
                                                Rp {{ number_format($pt->tarif_snapshot, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach ($proposal->proposalBiayaKomponen->where('kategori', 'fixed') as $pk)
                                        <tr>
                                            <td><strong>{{ $pk->nama_komponen }}</strong> </td>
                                            <td>Biaya Kelompok</td>
                                            <td class="text-end font-monospace">
                                                Rp {{ number_format($pk->nominal_satuan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Rincian Destinasi Wisata & Variable Cost -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <div class="card-title text-secondary mb-0">
                            Rincian Biaya / Orang
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Biaya</th>
                                        <th>Kategori</th>
                                        <th class="text-end">Nominal / Pax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proposal->proposalDestinasi as $pd)
                                        <tr>
                                            <td><strong>{{ $pd->destinasi->nama_destinasi ?? 'Objek Wisata' }}</strong>
                                            </td>
                                            <td><span class="badge bg-secondary">HTM Wisata</span></td>
                                            <td class="text-end font-monospace">
                                                Rp {{ number_format($pd->htm_snapshot, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach ($proposal->proposalBiayaKomponen->where('kategori', 'variable') as $pk)
                                        <tr>
                                            <td><strong>{{ $pk->nama_komponen }}</strong></td>
                                            <td><span class="badge bg-light text-dark border">Biaya Tambahan</span></td>
                                            <td class="text-end font-monospace">
                                                Rp {{ number_format($pk->nominal_satuan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <div class="card-title text-secondary mb-0"><i class="fas fa-list-check me-2"></i>Fasilitas
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($proposal->fasilitas)
                            <ol class="mb-0 ps-3">
                                @foreach (explode("\n", str_replace("\r", '', $proposal->fasilitas)) as $item)
                                    @if (trim($item) != '')
                                        <li class="mb-1">{{ trim($item) }}</li>
                                    @endif
                                @endforeach
                            </ol>
                        @else
                            <span class="text-muted font-italic">Tidak ada rincian fasilitas tambahan.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Ringkasan Kalkulasi & Aksi (Kanan) -->
            <div class="col-lg-4" id="sticky-column-parent">
                <div class="card border-secondary shadow" id="sticky-calculator">
                    <div class="card-header bg-secondary text-white rounded-top">
                        <div class="card-title text-white mb-0 d-flex align-items-center justify-content-between"
                            style="font-size: 15px;">
                            <span><i class="fas fa-calculator me-2"></i>Ringkasan Penawaran</span>
                            <span class="badge bg-white text-secondary fw-bold" style="font-size: 9px;">SUMMARY</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <!-- Total Peserta -->
                        <div class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small">Total Peserta:</span>
                            <h6 class="fw-bold text-dark mb-0">{{ $proposal->jumlah_peserta }} Pax</h6>
                        </div>

                        <!-- Total Biaya Operasional -->
                        @php
                            $totalBiayaOperasional =
                                $proposal->total_biaya_fixed + $proposal->biaya_var_per_pax * $proposal->jumlah_peserta;
                            $modalPerPax = $proposal->biaya_fixed_per_pax + $proposal->biaya_var_per_pax;
                        @endphp
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="mb-1">
                                <span class="text-muted small">Total Biaya Operasional:</span>
                                <h6 class="fw-bold text-dark mb-0">Rp
                                    {{ number_format($totalBiayaOperasional, 0, ',', '.') }}</h6>
                            </div>
                            <small class="text-muted font-italic d-block text-start" style="font-size: 11px;">
                                (Modal/Pax: Rp {{ number_format($modalPerPax, 0, ',', '.') }})
                            </small>
                        </div>

                        <!-- Target Profit Margin / Pax -->
                        <div class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small">Target Margin / Pax:</span>
                            <h6 class="fw-bold text-success mb-0">Rp
                                {{ number_format($proposal->margin_per_pax, 0, ',', '.') }}</h6>
                        </div>

                        <!-- Total Harga / Pax & Grand Total Omset -->
                        <div class="p-3 bg-light rounded border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small fw-bold text-uppercase" style="font-size: 11px;">Harga /
                                    Pax:</span>
                                <h3 class="fw-extrabold text-secondary mb-0">
                                    Rp {{ number_format($proposal->harga_akhir_per_pax, 0, ',', '.') }}
                                </h3>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small" style="font-size: 11px;">Total Omset:</span>
                                <h5 class="fw-bold text-success mb-0">
                                    Rp
                                    {{ number_format($proposal->harga_akhir_per_pax * $proposal->jumlah_peserta, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.proposal.export-pdf', $proposal->uuid) }}"
                                class="btn btn-secondary btn-round font-weight-bold shadow-sm">
                                <i class="fas fa-file-pdf me-1"></i> Cetak / Download PDF
                            </a>

                            @if ($proposal->status !== 'approved')
                                <a href="{{ route('admin.proposal.edit', $proposal->uuid) }}"
                                    class="btn btn-warning btn-round text-white font-weight-bold shadow-sm">
                                    <i class="fas fa-edit me-1"></i> Edit Proposal
                                </a>
                            @endif

                            <a href="{{ route('admin.proposal.index') }}" class="btn btn-outline-secondary btn-round">
                                Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Smart Sticky Handler -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
            updateStickyPosition();
        });
    </script>
@endsection
