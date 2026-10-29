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
                <span style="font-size: 14px;">Armada kendaraan</span>
            </li>
        </ul>
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Kelola Daftar Unit Armada</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.armada.create') }}" class="btn btn-secondary btn-round">
                    <i class="fa fa-plus me-1"></i> Tambah Armada
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover align-middle w-100">
                                <thead>
                                    <tr>
                                        <th>Jenis Armada</th>
                                        <th>Kapasitas</th>
                                        <th>Harga Sewa / Hari</th>
                                        <th class="text-end" style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($armadaList as $index => $armada)
                                        <tr>
                                            <td class="fw-bold">{{ $armada->jenis_armada }}</td>
                                            <td><span class="badge badge-info">{{ $armada->kapasitas }} Seat</span></td>
                                            <td class="text-success fw-bold">Rp
                                                {{ number_format($armada->harga_sewa, 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <div class="form-button-action justify-content-end">
                                                    <a href="{{ route('admin.armada.edit', $armada->uuid) }}"
                                                        class="btn btn-link btn-warning btn-lg p-1" title="Edit Data">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.armada.destroy', $armada->uuid) }}"
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
