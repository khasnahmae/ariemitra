@extends('layouts.admin')

@section('content')
    <style>
        /* Card & Layout Enhancements */
        .stat-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.08) !important;
        }

        .icon-shape {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Quick Access Action Card */
        .quick-action-card {
            border: 1px solid #f1f3f5;
            background-color: #ffffff;
            transition: all 0.2s ease-in-out;
            text-decoration: none !important;
        }

        .quick-action-card:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .quick-action-card .icon-wrapper {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* Table Styling */
        .table-custom th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #6c757d;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 0.85rem 1rem;
        }

        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
        }
    </style>

    <div class="page-inner py-4">
        <!-- Page Header -->
        <div class="d-flex align-items-md-center flex-column flex-md-row pb-4 border-bottom mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Dashboard Administrator</h3>
                <p class="text-muted small mb-0">Ringkasan statistik sistem & aktivitas proposal terbaru</p>
            </div>
            <div class="ms-md-auto mt-3 mt-md-0">
                <a href="{{ route('admin.proposal.create') }}" class="btn btn-primary btn-round px-3 shadow-sm">
                    <i class="fa fa-plus me-1"></i> Buat Proposal Baru
                </a>
            </div>
        </div>

        <!-- Alert Status -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Stats Row -->
        <div class="row g-3 mb-4">
            <!-- Total Omset Approved -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card card-round stat-card shadow-sm h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-success-subtle text-success me-3">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="overflow-hidden">
                                <span class="text-muted fw-semibold small d-block mb-1">Omset Approved</span>
                                <h4 class="fw-bold text-success mb-0 text-truncate">
                                    Rp {{ number_format($totalEstimasiOmset, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Proposal -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card card-round stat-card shadow-sm h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-primary-subtle text-primary me-3">
                                <i class="far fa-file-alt"></i>
                            </div>
                            <div>
                                <span class="text-muted fw-semibold small d-block mb-1">Total Proposal</span>
                                <h4 class="fw-bold text-dark mb-0">{{ $totalProposal }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Master Destinasi & Armada -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card card-round stat-card shadow-sm h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-info-subtle text-info me-3">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div>
                                <span class="text-muted fw-semibold small d-block mb-1">Destinasi / Bus</span>
                                <h4 class="fw-bold text-dark mb-0">{{ $totalDestinasi }} <span
                                        class="text-muted fs-6 font-normal">/ {{ $totalArmada }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paket Wisata Aktif -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card card-round stat-card shadow-sm h-100 mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-secondary-subtle text-secondary me-3">
                                <i class="fas fa-boxes-packing"></i>
                            </div>
                            <div>
                                <span class="text-muted fw-semibold small d-block mb-1">Paket Wisata</span>
                                <h4 class="fw-bold text-dark mb-0">{{ $totalPaketWisata }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Navigation -->
        <div class="mb-4">
            <div class="">
                <div class="">
                    <div class="card-header bg-transparent py-3">
                        <h6 class="card-title fw-bold mb-0 text-dark">
                            Akses Cepat Manajemen Data
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('admin.proposal.create') }}"
                                    class="card quick-action-card h-100 p-3 text-center rounded-3">
                                    <div class="icon-wrapper bg-primary-subtle text-primary mx-auto mb-2">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <span class="fw-semibold small text-dark">Buat Proposal</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('admin.destinasi.index') }}"
                                    class="card quick-action-card h-100 p-3 text-center rounded-3">
                                    <div class="icon-wrapper bg-danger-subtle text-danger mx-auto mb-2">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <span class="fw-semibold small text-dark">Destinasi</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('admin.armada.index') }}"
                                    class="card quick-action-card h-100 p-3 text-center rounded-3">
                                    <div class="icon-wrapper bg-info-subtle text-info mx-auto mb-2">
                                        <i class="fas fa-bus"></i>
                                    </div>
                                    <span class="fw-semibold small text-dark">Armada Bus</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('admin.rute-tol.index') }}"
                                    class="card quick-action-card h-100 p-3 text-center rounded-3">
                                    <div class="icon-wrapper bg-warning-subtle text-warning mx-auto mb-2">
                                        <i class="fas fa-road"></i>
                                    </div>
                                    <span class="fw-semibold small text-dark">Rute Tol</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('admin.paket-wisata.index') }}"
                                    class="card quick-action-card h-100 p-3 text-center rounded-3">
                                    <div class="icon-wrapper bg-secondary-subtle text-secondary mx-auto mb-2">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <span class="fw-semibold small text-dark">Paket Wisata</span>
                                </a>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('admin.galeri.index') }}"
                                    class="card quick-action-card h-100 p-3 text-center rounded-3">
                                    <div class="icon-wrapper bg-success-subtle text-success mx-auto mb-2">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <span class="fw-semibold small text-dark">Galeri Foto</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Proposals Table -->
        <div class="row">
            <div class="col-12">
                <div class="card card-round shadow-sm">
                    <div class="card-header bg-transparent py-3 d-flex align-items-center justify-content-between">
                        <h6 class="card-title fw-bold mb-0 text-dark">
                            <i class="fas fa-clock-rotate-left text-primary me-2"></i>Proposal Terbaru
                        </h6>
                        <a href="{{ route('admin.proposal.index') }}"
                            class="btn btn-outline-primary btn-sm btn-round px-3">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Klien / Tanggal</th>
                                        <th class="text-center">Peserta</th>
                                        <th>Harga / Pax</th>
                                        <th>Total Nilai</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentProposals as $proposal)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1">
                                                    {{ $proposal->kode_proposal }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $proposal->nama_klien }}</div>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($proposal->tanggal_proposal)->format('d M Y') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-light text-dark border">{{ $proposal->jumlah_peserta }}
                                                    Pax</span>
                                            </td>
                                            <td class="text-success fw-bold">
                                                Rp {{ number_format($proposal->harga_akhir_per_pax, 0, ',', '.') }}
                                            </td>
                                            <td class="fw-bold text-dark">
                                                Rp
                                                {{ number_format($proposal->harga_akhir_per_pax * $proposal->jumlah_peserta, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                @if ($proposal->status === 'approved')
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Approved</span>
                                                @elseif($proposal->status === 'sent')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">Sent</span>
                                                @elseif($proposal->status === 'rejected')
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">Rejected</span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="{{ route('admin.proposal.show', $proposal->uuid) }}"
                                                        class="btn btn-icon btn-sm btn-link text-primary"
                                                        data-bs-toggle="tooltip" title="Detail Proposal">
                                                        <i class="fa fa-eye fs-6"></i>
                                                    </a>
                                                    <a href="{{ route('admin.proposal.export-pdf', $proposal->uuid) }}"
                                                        class="btn btn-icon btn-sm btn-link text-info"
                                                        data-bs-toggle="tooltip" title="Cetak PDF">
                                                        <i class="fa fa-file-pdf fs-6"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-50"></i>
                                                Belum ada data proposal penawaran.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
