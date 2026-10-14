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

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">Alamat Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email') }}" placeholder="contoh@domain.com" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="role" class="form-label fw-bold">Hak Akses / Role <span
                                    class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Akses
                                    Operasional)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Akses Penuh +
                                    Manajemen User)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold">Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-round">
                            <i class="fas fa-save me-1"></i> Simpan User
                        </button>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
