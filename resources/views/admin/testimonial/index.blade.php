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
                <span style="font-size: 14px;">Testimoni Client</span>
            </li>
        </ul>

        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Kelola Testimoni Client</h3>
                <p class="text-muted mb-0">Manajemen ulasan dan rating real yang dikirimkan oleh pemesan paket wisata.</p>
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
                                        <th>Nama Client</th>
                                        <th>Paket Wisata</th>
                                        <th>Rating</th>
                                        <th>Ulasan Testimoni</th>
                                        <th>Status Tampil</th>
                                        <th class="text-end" style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testimonials as $t)
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $t->user->nama ?? 'Client' }}</strong><br>
                                                <small class="text-muted">{{ $t->jabatan_profesi }}</small>
                                            </td>
                                            <td class="small">{{ $t->booking->tujuan_wisata ?? '-' }}</td>
                                            <td class="text-warning">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fa {{ $i <= $t->rating ? 'fa-star' : 'fa-star-o' }}"></i>
                                                @endfor
                                            </td>
                                            <td class="small">"{{ $t->pesan_testimoni }}"</td>
                                            <td>
                                                @if ($t->is_published)
                                                    <span class="badge bg-success">Tampil</span>
                                                @else
                                                    <span class="badge bg-secondary">Sembunyi</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="form-button-action justify-content-end gap-1">
                                                    <!-- Toggle Status Publikasi -->
                                                    <form action="{{ route('admin.testimonial.toggle', $t->uuid) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-link btn-{{ $t->is_published ? 'warning' : 'info' }} btn-lg p-1"
                                                            title="{{ $t->is_published ? 'Sembunyikan' : 'Tampilkan' }}">
                                                            <i
                                                                class="fa {{ $t->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Hapus Testimoni -->
                                                    <form action="{{ route('admin.testimonial.destroy', $t->uuid) }}"
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
