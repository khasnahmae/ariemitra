@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">Edit User / Ubah Password</h4>
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

        <form action="{{ route('admin.user.update', $user->uuid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="container-fluid">
                <div class="col">
                    <div class="form-group mb-3">
                        <label for="nama" class="fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" id="nama"
                            value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="fw-bold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" id="email"
                            value="{{ old('email', $user->email) }}" placeholder="contoh@domain.com" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="role" class="fw-bold">Hak Akses / Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff (Akses
                                Operasional)</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin (Akses
                                Penuh + Manajemen User)</option>
                        </select>
                    </div>

                    <div class="form-group my-2">
                        <div class="p-3 bg-light rounded border">
                            <h6 class="fw-bold mb-1"><i class="fas fa-key me-1 text-warning"></i> Ubah Password (Opsional)
                            </h6>
                            <p class="text-muted small mb-0">Biarkan kolom password di bawah ini kosong jika tidak ingin
                                mengubah password akun ini.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="password" class="fw-bold">Password Baru</label>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Isi jika ingin mengganti password">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="password_confirmation" class="fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group d-flex gap-2 pt-3">
                <button type="submit" class="btn btn-secondary btn-round">
                    <i class="fas fa-save me-1"></i> Perbarui Data User
                </button>
                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-round">Batal</a>
            </div>
        </form>
    </div>
@endsection
