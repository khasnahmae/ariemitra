@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Tambah Data Armada Baru</h4>
            <a href="{{ route('admin.armada.index') }}" class="btn btn-outline-secondary btn-round">
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

        <form action="{{ route('admin.armada.store') }}" method="POST">
            @csrf
            <div class="container-fluid">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="jenis_armada" class="fw-bold">Jenis / Nama Armada <span
                                class="text-danger">*</span></label>
                        <input type="text" name="jenis_armada" class="form-control" id="jenis_armada"
                            value="{{ old('jenis_armada') }}" placeholder="Contoh: Big Bus HDD / Medium Bus" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="kapasitas" class="fw-bold">Kapasitas Kursi (Seat) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="kapasitas" class="form-control" id="kapasitas" min="1"
                            value="{{ old('kapasitas') }}" placeholder="Contoh: 50" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="is_include_solar" class="fw-bold">Status BBM / Solar <span
                                class="text-danger">*</span></label>
                        <select name="is_include_solar" id="is_include_solar" class="form-select" required>
                            <option value="ya" {{ old('is_include_solar', 'ya') == 'ya' ? 'selected' : '' }}>Include BBM
                                / Solar</option>
                            <option value="tidak" {{ old('is_include_solar') == 'tidak' ? 'selected' : '' }}>Exclude BBM /
                                Solar (Tanpa Solar)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="harga_sewa" class="fw-bold">Harga Sewa (Rp/Hari) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="harga_sewa" class="form-control" id="harga_sewa" min="0"
                            value="{{ old('harga_sewa') }}" placeholder="Contoh: 3500000" required>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-save me-1"></i> Simpan Data Armada
                </button>
                <a href="{{ route('admin.armada.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
