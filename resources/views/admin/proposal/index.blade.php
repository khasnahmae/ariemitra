@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <ul class="mb-2 ps-0 d-flex align-items-center gap-3 text-muted text-small style-none" style="list-style: none;">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}" class="text-primary">
                    <i class="fas fa-home text-secondary" style="font-size: 18px;"></i>
                </a>
            </li>
            <li class="separator"><i class="fas fa-chevron-right text-muted" style="font-size: 12px;"></i></li>
            <li class="nav-item">
                <span style="font-size: 14px;">Proposal</span>
            </li>
        </ul>
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Kelola Proposal Perjalanan</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.proposal.create') }}" class="btn btn-secondary btn-round">
                    <i class="fa fa-plus me-1"></i> Buat Proposal Baru
                </a>
            </div>
        </div>

        <!-- Alert Flash Messages -->
        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        <!-- Main Card Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover align-middle w-100">
                                <thead>
                                    <tr>
                                        <th>Kode Proposal</th>
                                        <th>Nama Klien</th>
                                        <th>Peserta</th>
                                        <th>Harga / Pax</th>
                                        <th>Status</th>
                                        <th class="text-end" style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proposalList as $p)
                                        <tr>
                                            <td>
                                                <span
                                                    class="badge badge-secondary fw-bold font-monospace">{{ $p->kode_proposal }}</span>
                                            </td>
                                            <td class="fw-bold">{{ $p->nama_klien }}</td>
                                            <td>{{ $p->jumlah_peserta }} Pax</td>
                                            <td class="text-success fw-bold">
                                                Rp {{ number_format($p->harga_akhir_per_pax, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if ($p->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($p->status == 'sent')
                                                    <span class="badge badge-warning">Sent</span>
                                                @elseif($p->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-count">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="form-button-action justify-content-end gap-1">
                                                    <a href="{{ route('admin.proposal.show', $p->uuid) }}"
                                                        class="btn btn-link btn-primary btn-lg p-1" data-bs-toggle="tooltip"
                                                        title="Detail Proposal">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    @if ($p->status !== 'approved')
                                                        <a href="{{ route('admin.proposal.edit', $p->uuid) }}"
                                                            class="btn btn-link btn-warning btn-lg p-1"
                                                            data-bs-toggle="tooltip" title="Edit Proposal">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <!-- Opsional: Tampilan Disabled Jika Ingin Pengguna Tetap Melihat Tombol Tapi Tidak Bisa Diklik -->
                                                        {{-- <button class="btn btn-link btn-secondary btn-lg p-1 opacity-50"
                                                            data-bs-toggle="tooltip"
                                                            title="Proposal Approved Tidak Dapat Diubah" disabled>
                                                            <i class="fa fa-edit"></i>
                                                        </button> --}}
                                                    @endif

                                                    <a href="{{ route('admin.proposal.export-pdf', $p->uuid) }}"
                                                        class="btn btn-link btn-info btn-lg p-1" data-bs-toggle="tooltip"
                                                        title="Cetak PDF">
                                                        <i class="fa fa-file-pdf"></i>
                                                    </a>

                                                    @if ($p->status !== 'approved')
                                                        <form action="{{ route('admin.proposal.destroy', $p->uuid) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-link btn-danger btn-lg p-1 delete-button"
                                                                data-bs-toggle="tooltip" title="Hapus Proposal">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Opsional: Disabled State untuk Hapus -->
                                                        {{-- <button class="btn btn-link btn-secondary btn-lg p-1 opacity-50"
                                                            data-bs-toggle="tooltip"
                                                            title="Proposal Approved Tidak Dapat Dihapus" disabled>
                                                            <i class="fa fa-times"></i>
                                                        </button> --}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
