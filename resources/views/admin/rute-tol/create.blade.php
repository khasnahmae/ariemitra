@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Tambah Rute Tol Baru</h4>
            <a href="{{ route('admin.rute-tol.index') }}" class="btn btn-outline-secondary btn-round">
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

        <form action="{{ route('admin.rute-tol.store') }}" method="POST">
            @csrf
            <div class="container-fluid">
                <div class="col">

                    <div class="form-group">
                        <label for="nama_rute" class="fw-bold">Nama Rute Tol <span class="text-danger">*</span></label>
                        <input type="text" name="nama_rute" class="form-control" id="nama_rute"
                            value="{{ old('nama_rute') }}" placeholder="Contoh: Jakarta - Semarang (Full Tol)" required>
                    </div>


                    <div class="form-group">
                        <label for="golongan" class="fw-bold">Golongan Kendaraan <span class="text-danger">*</span></label>
                        <input type="text" name="golongan" class="form-control" id="golongan"
                            value="{{ old('golongan', 'Golongan I / Bus') }}" placeholder="Contoh: Golongan I / Bus"
                            required>
                    </div>


                    <div class="form-group">
                        <label for="tarif_total" class="fw-bold">Tarif Total (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="tarif_total" class="form-control" id="tarif_total" min="0"
                            value="{{ old('tarif_total') }}" placeholder="Contoh: 450000" required>
                    </div>

                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-save me-1"></i> Simpan Rute Tol
                </button>
                <a href="{{ route('admin.rute-tol.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
