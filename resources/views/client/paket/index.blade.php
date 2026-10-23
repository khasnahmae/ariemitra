@extends('layouts.admin')

@section('content')
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-1">Daftar Paket Wisata</h3>
                <h6 class="op-7 mb-0">Pilih paket perjalanan terbaik yang kami tawarkan untuk Anda.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <form action="{{ route('client.paket.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari paket..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="row">
            @forelse($proposalList as $p)
                <div class="col-md-4 mb-4">
                    <div class="card card-round h-100 shadow-sm border">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-secondary font-monospace">{{ $p->nama_sub_paket }}</span>
                                <span class="badge bg-info">{{ $p->jumlah_hari }} Hari {{ $p->jumlah_malam }} Malam</span>
                            </div>
                            <h5 class="card-title fw-bold text-primary mb-2">
                                {{ $p->PaketWisata->nama_paket ?? 'Paket Wisata' }}
                            </h5>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-users me-1"></i> Kapasitas: {{ $p->jumlah_peserta }} Pax
                            </p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">Harga / Pax:</small>
                                    <h5 class="fw-bold text-success mb-0">Rp
                                        {{ number_format($p->harga_akhir_per_pax, 0, ',', '.') }}</h5>
                                </div>
                                <a href="{{ route('client.paket.show', $p->uuid) }}"
                                    class="btn btn-primary btn-round w-100">
                                    <i class="fa fa-eye me-1"></i> Lihat Detail & Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada paket wisata yang tersedia.</h5>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $proposalList->links() }}
        </div>
    </div>
@endsection
