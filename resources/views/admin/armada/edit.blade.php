@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Edit Data Armada</h4>
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

        <form action="{{ route('admin.armada.update', $armada->uuid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="container-fluid">
                <div class="col">
                    <div class="form-group">
                        <label for="jenis_armada" class="fw-bold">Jenis / Nama Armada <span
                                class="text-danger">*</span></label>
                        <input type="text" name="jenis_armada" class="form-control" id="jenis_armada"
                            value="{{ old('jenis_armada', $armada->jenis_armada) }}"
                            placeholder="Contoh: Big Bus HDD / Medium Bus" required>
                    </div>
                    <div class="form-group">
                        <label for="kapasitas" class="fw-bold">Kapasitas Kursi (Seat) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="kapasitas" class="form-control" id="kapasitas" min="1"
                            value="{{ old('kapasitas', $armada->kapasitas) }}" placeholder="Contoh: 50" required>
                    </div>
                    <div class="form-group">
                        <label for="sewa_inc_solar" class="fw-bold">Harga Sewa Inc. Solar (Rp/Hari) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="sewa_inc_solar" class="form-control" id="sewa_inc_solar" min="0"
                            value="{{ old('sewa_inc_solar', (int) $armada->sewa_inc_solar) }}" placeholder="Contoh: 3500000"
                            required>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-save me-1"></i> Perbarui Data Armada
                </button>
                <a href="{{ route('admin.armada.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
