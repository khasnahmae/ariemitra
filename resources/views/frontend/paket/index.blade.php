@extends('layouts.app')

@section('content')
    <!-- HERO / BREADCRUMB HEADER -->
    <div class="container">
        <div class="row no-gutters">
            <div class="col-md-9 pb-5 text-start">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('frontend.home') }}">Beranda <i
                                class="fa fa-chevron-right"></i></a></span>
                    <span>Paket Wisata <i class="fa fa-chevron-right"></i></span>
                </p>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH SECTION -->
    <section class="ftco-section ftco-no-pb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="search-wrap-1 ftco-animate">
                        <form action="{{ route('frontend.paket.index') }}" method="GET" class="search-property-1">
                            <div class="row no-gutters">
                                <div class="col-lg d-flex">
                                    <div class="form-group p-4 border-0">
                                        <label for="search">Nama Paket Wisata</label>
                                        <div class="form-field">
                                            <div class="icon"><span class="fa fa-search"></span></div>
                                            <input type="text" name="search" id="search" class="form-control"
                                                value="{{ request('search') }}" placeholder="Cari nama/sub paket...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg d-flex">
                                    <div class="form-group p-4">
                                        <label for="max_price">Batas Harga Maksimal</label>
                                        <div class="form-field">
                                            <div class="icon"><span class="fa fa-tag"></span></div>
                                            <input type="number" name="max_price" id="max_price" class="form-control"
                                                value="{{ request('max_price') }}" placeholder="Batas Harga (Rp)...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg d-flex">
                                    <div class="form-group d-flex w-100 border-0">
                                        <div class="form-field w-100 align-items-center d-flex gap-2">
                                            <input type="submit" value="Filter Paket"
                                                class="align-self-stretch form-control btn btn-primary">
                                            @if (request('search') || request('max_price'))
                                                <a href="{{ route('frontend.paket.index') }}"
                                                    class="btn btn-secondary align-self-stretch d-flex align-items-center justify-content-center px-3"
                                                    title="Reset Filter">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LISTING PAKET WISATA -->
    <section class="ftco-section">
        <div class="container">
            @if ($paketList->count() > 0)
                <div class="row">
                    @foreach ($paketList as $paket)
                        @php
                            // Logika Mengambil Gambar dari Destinasi Pertama
                            $firstDestinasi = $paket->proposalDestinasi->first()?->destinasi;
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
                        <div class="col-md-4 ftco-animate">
                            <div class="project-wrap">
                                <a href="{{ route('frontend.paket.show', $paket->uuid) }}" class="img"
                                    style="background-image: url('{{ $paketImg }}');">
                                    <span class="price">Rp
                                        {{ number_format($paket->harga_akhir_per_pax, 0, ',', '.') }}/pax</span>
                                </a>
                                <div class="text p-4">
                                    <span class="days"><i class="fa fa-clock-o mr-1"></i> {{ $paket->jumlah_hari }} Hari
                                        @if ($paket->jumlah_malam > 0)
                                            {{ $paket->jumlah_malam }} Malam
                                        @endif
                                    </span>
                                    <h3>
                                        <a href="{{ route('frontend.paket.show', $paket->uuid) }}">
                                            {{ $paket->PaketWisata->nama_paket ?? 'Paket Wisata' }} - Sub
                                            {{ $paket->nama_sub_paket }}
                                        </a>
                                    </h3>

                                    <p class="location mb-2">
                                        <span class="fa fa-map-marker"></span>
                                        @foreach ($paket->proposalDestinasi->take(2) as $pDest)
                                            {{ $pDest->destinasi->nama_destinasi ?? '' }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                        @if ($paket->proposalDestinasi->count() > 2)
                                            <small class="text-muted">+{{ $paket->proposalDestinasi->count() - 2 }}
                                                lainnya</small>
                                        @endif
                                    </p>

                                    <ul class="mb-0 pt-2 border-top">
                                        <li><span class="fa fa-users text-info mr-1"></span> Min.
                                            {{ $paket->jumlah_peserta }} Pax</li>
                                        <li><span class="fa fa-check-circle text-success mr-1"></span> All-In</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- PAGINATION -->
                <div class="row mt-5">
                    <div class="col text-center">
                        <div class="block-27">
                            {{ $paketList->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="row py-5">
                    <div class="col-md-12 text-center">
                        <div class="p-5 bg-light rounded">
                            <span class="fa fa-folder-open-o fa-3x text-muted mb-3 d-block"></span>
                            <h4>Paket Wisata Tidak Ditemukan</h4>
                            <p class="text-muted">Coba gunakan kata kunci lain atau hapus filter harga.</p>
                            <a href="{{ route('frontend.paket.index') }}" class="btn btn-primary px-4 py-2 mt-2">Reset
                                Filter</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
