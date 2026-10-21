@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Tambah User / Staff Baru</h4>
            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-round">
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

        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="container-fluid">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="nama" class="fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}"
                            placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="fw-bold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}"
                            placeholder="contoh@domain.com" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="role" class="fw-bold">Hak Akses / Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Akses Operasional)
                            </option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Akses Penuh +
                                Manajemen User)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="password" class="fw-bold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="password_confirmation" class="fw-bold">Konfirmasi Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation" placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-save me-1"></i> Simpan User
                </button>
                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
