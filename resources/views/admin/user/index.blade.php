@extends('layouts.admin')

@section('content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="card-title mb-0">Manajemen User & Staff</h4>
                <p class="text-muted small mb-0">Kelola akun admin dan staf serta pembagian hak akses sistem.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-round">
                <i class="fas fa-plus me-1"></i> Tambah User Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th width="15%" class="text-center">Role / Hak Akses</th>
                                <th width="15%" class="text-center">Tanggal Dibuat</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $users->firstItem() + $index }}</td>
                                    <td class="fw-bold text-dark">{{ $item->nama }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td class="text-center">
                                        @if ($item->role === 'admin')
                                            <span class="badge bg-danger px-3 py-2">Admin</span>
                                        @else
                                            <span class="badge bg-info text-dark px-3 py-2">Staff</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted small">
                                        {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.edit', $item->uuid) }}"
                                                class="btn btn-sm btn-outline-warning" title="Edit Data / Password">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (auth()->id() !== $item->id)
                                                <form action="{{ route('admin.users.destroy', $item->uuid) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Hapus User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($users->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
