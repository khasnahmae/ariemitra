@extends('layouts.app')

@section('content')
    @php
        // Ambil foto pertama dari galeri paket wisata (jika ada)
        $firstGallery = $paket->galeri->first();

        // Tentukan URL gambar: jika galeri ada, pakai dari storage; jika tidak, pakai default
        $headerBg = $firstGallery ? asset('storage/' . $firstGallery->file_gambar) : asset('frontend/images/bg_1.jpg');
    @endphp

    <!-- HERO / BREADCRUMB HEADER -->
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('{{ $headerBg }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
                <div class="col-md-9 ftco-animate pb-5 text-center">
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('frontend.home') }}">Beranda <i
                                    class="fa fa-chevron-right"></i></a></span>
                        <span class="mr-2"><a href="{{ route('frontend.paket.index') }}">Paket Wisata <i
                                    class="fa fa-chevron-right"></i></a></span>
                        <span>Detail <i class="fa fa-chevron-right"></i></span>
                    </p>
                    <h1 class="mb-0 bread">{{ $paket->nama_paket }}</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- DETAIL CONTENT SECTION -->
    <section class="ftco-section">
        <div class="container">
            <div class="row">

                <!-- KOLOM KIRI: MAIN DETAILS -->
                <div class="col-lg-8 ftco-animate">
                    <div class="mb-4">
                        <a href="{{ route('frontend.paket.index') }}" class="btn btn-outline-primary btn-sm mb-3">
                            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Katalog
                        </a>
                        <span class="badge badge-primary px-3 py-2 font-weight-normal float-right">
                            <i class="fa fa-clock-o mr-1"></i> Durasi: {{ $paket->durasi }}
                        </span>
                    </div>

                    <h2 class="mb-3 font-weight-bold text-dark">{{ $paket->nama_paket }}</h2>
                    <p class="text-muted mb-4">Kode Paket: #PKT-{{ str_pad($paket->id, 4, '0', STR_PAD_LEFT) }}</p>

                    <!-- DESTINASI YANG DIKUNJUNGI -->
                    <div class="mb-5">
                        <h4 class="mb-3 font-weight-bold">Destinasi Yang Dikunjungi</h4>
                        <div class="row">
                            @foreach ($paket->destinasi as $dest)
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 bg-light rounded d-flex align-items-center">
                                        <div class="icon mr-3 text-primary">
                                            <span class="fa fa-map-marker fa-2x"></span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 1rem;">
                                                {{ $dest->nama_destinasi }}</h5>
                                            <small class="text-muted">{{ $dest->lokasi }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- FASILITAS & LAYANAN -->
                    <div class="mb-5">
                        <h4 class="mb-3 font-weight-bold">Fasilitas & Layanan Paket</h4>
                        <div class="p-4 bg-light rounded text-dark" style="white-space: pre-line; line-height: 1.8;">
                            {{ $paket->fasilitas }}
                        </div>
                    </div>

                    <!-- DOKUMENTASI / GALERI FOTO -->
                    @if ($paket->galeri->count() > 0)
                        <div class="mb-5">
                            <h4 class="mb-3 font-weight-bold">Dokumentasi Destinasi</h4>
                            <div class="row">
                                @foreach ($paket->galeri as $foto)
                                    <div class="col-md-4 col-6 mb-3">
                                        <a href="{{ asset('storage/' . $foto->file_gambar) }}"
                                            class="image-popup img d-flex align-items-center justify-content-center rounded overflow-hidden"
                                            style="background-image: url('{{ asset('storage/' . $foto->file_gambar) }}'); height: 160px; background-size: cover; background-position: center;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- KOLOM KANAN: SIDEBAR ORDER -->
                <div class="col-lg-4 sidebar ftco-animate">
                    <div class="sidebar-box bg-light p-4 rounded shadow-sm border">
                        <span class="text-uppercase text-muted font-weight-bold small d-block mb-1">Estimasi
                            Penawaran</span>
                        <h2 class="text-success font-weight-bold mb-3">
                            Rp {{ number_format($paket->harga_mulai_from, 0, ',', '.') }}
                            <small class="text-muted font-weight-normal" style="font-size: 0.9rem;">/ pax</small>
                        </h2>

                        <hr class="my-3">

                        <ul class="list-unstyled text-secondary small mb-4">
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Tiket HTM Wisata Termasuk</li>
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Transportasi & Tol Termasuk
                            </li>
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Tour Leader & Crew Service</li>
                        </ul>

                        <a href="{{ $waLink }}" target="_blank"
                            class="btn btn-success btn-block py-3 font-weight-bold shadow-sm">
                            <i class="fa fa-whatsapp fa-lg mr-2"></i> Minta Proposal / Order via WA
                        </a>

                        <p class="text-muted text-center small mt-3 mb-0" style="font-size: 0.75rem;">
                            *Harga akhir disesuaikan dengan total kuota peserta & kustomisasi rute proposal.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
