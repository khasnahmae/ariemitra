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
                <span style="font-size: 14px;">Galeri Dokumentasi</span>
            </li>
        </ul>
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Galeri Dokumentasi Perjalanan</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.galeri.create') }}" class="btn btn-secondary btn-round">
                    <i class="fa fa-plus me-1"></i> Unggah Foto Baru
                </a>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        <div class="row">
            @forelse($galeriList as $galeri)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card card-round h-100 shadow-sm">
                        <img src="{{ asset('storage/' . $galeri->file_gambar) }}" class="card-img-top"
                            alt="{{ $galeri->judul }}"
                            style="height: 180px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-1 text-truncate">{{ $galeri->judul }}</h6>
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-box me-1"></i>
                                    {{ $galeri->paketWisata->nama_paket ?? 'Umum / Non-Paket' }}
                                </small>
                            </div>
                            <div class="d-flex justify-content-end gap-1 pt-2 border-top">
                                <a href="{{ route('admin.galeri.edit', $galeri->uuid) }}"
                                    class="btn btn-link btn-warning btn-sm p-1" title="Edit Foto">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.galeri.destroy', $galeri->uuid) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-danger btn-sm p-1 delete-button"
                                        title="Hapus Foto">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card card-round p-5 text-center text-muted">
                        <i class="fas fa-images fa-3x mb-3"></i>
                        <p class="mb-0">Belum ada foto dokumentasi yang diunggah.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center pt-3">
            {{ $galeriList->links() }}
        </div>
    </div>
@endsection
