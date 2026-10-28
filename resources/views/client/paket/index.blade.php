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
            <div class="row">
                @forelse($proposalList as $p)
                    @php
                        // Logika Mengambil Gambar dari Destinasi Pertama
                        $firstDestinasi = $p->proposalDestinasi->first()?->destinasi;
                        if ($firstDestinasi && $firstDestinasi->foto) {
                            $paketImg = \Illuminate\Support\Str::startsWith($firstDestinasi->foto, [
                                'http://',
                                'https://',
                            ])
                                ? $firstDestinasi->foto
                                : asset('storage/' . $firstDestinasi->foto);
                        } else {
                            $paketImg = asset('frontend/images/destination-1.jpg');
                        }
                    @endphp

                    <div class="col-md-4 mb-4">
                        <div class="card card-round h-100 shadow-sm border overflow-hidden">
                            <!-- Thumbnail Gambar Paket -->
                            <div class="position-relative"
                                style="height: 180px; background-image: url('{{ $paketImg }}'); background-size: cover; background-position: center;">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span
                                        class="badge bg-secondary font-monospace shadow-sm">{{ $p->nama_sub_paket }}</span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-info shadow-sm">
                                        <i class="fa fa-clock me-1"></i> {{ $p->jumlah_hari }} Hari
                                        @if ($p->jumlah_malam > 0)
                                            {{ $p->jumlah_malam }} Malam
                                        @endif
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 start-0 end-0 p-2 bg-dark bg-opacity-50 text-white">
                                    <small class="fw-bold">
                                        {{ $p->jumlah_peserta }} Pax
                                    </small>
                                </div>
                            </div>

                            <!-- Body Card -->
                            <div class="card-body d-flex flex-column p-3">
                                <h5 class="card-title fw-bold text-primary mb-2">
                                    {{ $p->PaketWisata->nama_paket ?? 'Paket Wisata' }}
                                </h5>

                                <!-- Lokasi Destinasi -->
                                <p class="text-muted small mb-3">
                                    <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                    @foreach ($p->proposalDestinasi->take(2) as $pDest)
                                        {{ $pDest->destinasi->nama_destinasi ?? '' }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if ($p->proposalDestinasi->count() > 2)
                                        <span class="fw-semibold text-secondary">+{{ $p->proposalDestinasi->count() - 2 }}
                                            lainnya</span>
                                    @endif
                                </p>

                                <!-- Bottom Action Section -->
                                <div class="mt-auto pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">Harga / Pax:</small>
                                        <h5 class="fw-bold text-success mb-0">Rp
                                            {{ number_format($p->harga_akhir_per_pax, 0, ',', '.') }}</h5>
                                    </div>

                                    <a href="{{ route('client.paket.show', $p->uuid) }}"
                                        class="btn btn-primary btn-round w-100 fw-bold">
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
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $proposalList->links() }}
        </div>
    </div>
@endsection
