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
                <span style="font-size: 14px;">Destinasi Wisata</span>
            </li>
        </ul>
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Kelola Destinasi Wisata</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.destinasi.create') }}" class="btn btn-secondary btn-round">
                    <i class="fa fa-plus me-1"></i> Tambah Destinasi
                </a>
            </div>
        </div>

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

        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover align-middle w-100">
                                <thead>
                                    <tr>
                                        {{-- <th>No</th> --}}
                                        <th>Nama Destinasi</th>
                                        <th>Lokasi / Kota</th>
                                        <th>HTM / Pax</th>
                                        <th class="text-end" style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($destinasiList as $index => $destinasi)
                                        <tr>
                                            {{-- <td>{{ $index + 1 }}</td> --}}
                                            <td class="fw-bold">{{ $destinasi->nama_destinasi }}</td>
                                            <td><i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                {{ $destinasi->lokasi }}</td>
                                            <td class="text-success fw-bold">Rp
                                                {{ number_format($destinasi->htm_per_orang, 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <div class="form-button-action justify-content-end">
                                                    <a href="{{ route('admin.destinasi.edit', $destinasi->uuid) }}"
                                                        class="btn btn-link btn-warning btn-lg p-1" title="Edit Data">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.destinasi.destroy', $destinasi->uuid) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-link btn-danger btn-lg p-1 delete-button"
                                                            title="Hapus Data">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
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
