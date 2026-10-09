@extends('layouts.app')

@section('content')
    <!-- Hero Banner Section -->
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('/images/img1.png') }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-center" data-scrollax-parent="true">
                <div class="col-md-7 ftco-animate">
                    <span class="subheading">Solusi Perjalanan Wisata & Study Tour</span>
                    <h1 class="mb-4">Jelajahi Keindahan Nusantara Dengan Biaya Transparan</h1>
                    <p class="caps">Menyediakan paket tour sekolah, instansi, keluarga, dan rombongan umum dengan kalkulasi
                        biaya akurat.</p>
                    <p>
                        <a href="{{ route('frontend.paket.index') }}" class="btn btn-primary py-3 px-4 mr-2">Lihat Paket
                            Tour</a>
                        <a href="{{ route('frontend.kontak') }}"
                            class="btn btn-white btn-outline-white py-3 px-4">Konsultasi Rombongan</a>
                    </p>
                </div>
                <a href="https://vimeo.com/45830194"
                    class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
                    <span class="fa fa-play"></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Search Section -->
    <section class="ftco-section ftco-no-pb ftco-no-pt">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ftco-search d-flex justify-content-center">
                        <div class="row w-100">
                            <div class="col-md-12 nav-link-wrap">
                                <div class="nav nav-pills text-center" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <a class="nav-link active mr-md-1" id="v-pills-1-tab" data-toggle="pill"
                                        href="#v-pills-1" role="tab" aria-controls="v-pills-1" aria-selected="true">Cari
                                        Paket Tour Rombongan</a>
                                </div>
                            </div>
                            <div class="col-md-12 tab-wrap">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel"
                                        aria-labelledby="v-pills-nextgen-tab">
                                        <form action="{{ route('frontend.paket.index') }}" method="GET"
                                            class="search-property-1">
                                            <div class="row no-gutters">
                                                <div class="col-md-5 d-flex">
                                                    <div class="form-group p-4 border-0">
                                                        <label for="search">Nama Paket / Destinasi</label>
                                                        <div class="form-field">
                                                            <div class="icon"><span class="fa fa-search"></span></div>
                                                            <input type="text" name="search" class="form-control"
                                                                placeholder="Misal: Jogja, Bali, Bromo..."
                                                                value="{{ request('search') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 d-flex">
                                                    <div class="form-group p-4">
                                                        <label for="max_price">Anggaran Maksimal (Per Pax)</label>
                                                        <div class="form-field">
                                                            <div class="select-wrap">
                                                                <div class="icon"><span class="fa fa-chevron-down"></span>
                                                                </div>
                                                                <select name="max_price" class="form-control">
                                                                    <option value="">Semua Range Anggaran</option>
                                                                    <option value="500000"
                                                                        {{ request('max_price') == '500000' ? 'selected' : '' }}>
                                                                        Di bawah Rp 500.000</option>
                                                                    <option value="1000000"
                                                                        {{ request('max_price') == '1000000' ? 'selected' : '' }}>
                                                                        Di bawah Rp 1.000.000</option>
                                                                    <option value="2000000"
                                                                        {{ request('max_price') == '2000000' ? 'selected' : '' }}>
                                                                        Di bawah Rp 2.000.000</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex">
                                                    <div class="form-group d-flex w-100 border-0">
                                                        <div class="form-field w-100 align-items-center d-flex">
                                                            <input type="submit" value="Cari Paket"
                                                                class="align-self-stretch form-control btn btn-primary p-0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services / Key Advantages -->
    <section class="ftco-section services-section">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-6 order-md-last heading-section pl-md-5 ftco-animate d-flex align-items-center">
                    <div class="w-100">
                        <span class="subheading">Keunggulan Layanan</span>
                        <h2 class="mb-4">Saatnya Memulai Petualangan Seru Anda</h2>
                        <p>Kami hadir memberikan kemudahan perencanaan tur rombongan dengan perhitungan biaya yang terbuka,
                            transparan, dan dapat disesuaikan dengan anggaran kebutuhan Anda.</p>
                        <p><a href="{{ route('frontend.paket.index') }}" class="btn btn-primary py-3 px-4">Jelajahi Paket
                                Wisata</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-2 d-block img"
                                style="background-image: url('{{ asset('images/img5.png') }}');">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-bus"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Armada Executive</h3>
                                    <p>Format Seat 2-2, Full AC, Multimedia Karaoke, Audio
                                        Premium, serta Reclining Seat yang nyaman.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-4 d-block img"
                                style="background-image: url('{{ asset('images/img6.png') }}')">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-ticket"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Tiket & Konsumsi Berkualitas</h3>
                                    <p>Tiket masuk semua lokasi wisata terintegrasi
                                        tanpa antre, makan prasmanan/box
                                        higienis, snack perjalanan, dan air mineral.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-3 d-block img"
                                style="background-image: url('{{ asset('images/img8.png') }}')">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-shield"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Terlindungi Asuransi</h3>
                                    <p>Seluruh peserta terlindungi jaminan asuransi keselamatan perjalanan wisata.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-1 d-block img"
                                style="background-image: url('{{ asset('images/img7.png') }}')">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-calculator"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Hitung Draf Otomatis</h3>
                                    <p>Sistem kalkulasi biaya fixed & variable secara akurat untuk sekolah/instansi.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Carousel Featured Destinations -->
    @if ($destinasiPilihan->count() > 0)
        <section class="ftco-section img ftco-select-destination">
            <div class="container">
                <div class="row justify-content-center pb-4">
                    <div class="col-md-12 heading-section text-center ftco-animate">
                        <span class="subheading">Destinasi Pilihan</span>
                        <h2 class="mb-4">Objek Wisata Favorit</h2>
                    </div>
                </div>
            </div>
            <div class="container container-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="carousel-destination owl-carousel ftco-animate">
                            @foreach ($destinasiPilihan as $dest)
                                <div class="item">
                                    <div class="project-destination">
                                        @php
                                            $bgImage = $dest->foto
                                                ? asset('storage/' . $dest->foto)
                                                : asset('frontend/images/place-1.jpg');
                                        @endphp

                                        <a href="#" class="img"
                                            style="background-image: url('{{ $bgImage }}');">
                                            <div class="text">
                                                <h3>{{ $dest->nama_destinasi }}</h3>
                                                <span>HTM: Rp
                                                    {{ number_format($dest->htm_per_orang, 0, ',', '.') }}/pax</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Popular Tour Packages Section -->
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center pb-4">
                <div class="col-md-12 heading-section text-center ftco-animate">
                    <span class="subheading">Pilihan Rombongan</span>
                    <h2 class="mb-4">Paket Wisata Populer</h2>
                </div>
            </div>
            <div class="row">
                @forelse($paketPopuler as $paket)
                    <div class="col-md-4 ftco-animate">
                        <div class="project-wrap">
                            <a href="{{ route('frontend.paket.show', $paket->slug) }}" class="img"
                                style="background-image: url('{{ $paket->galeri->count() > 0 ? asset('storage/' . $paket->galeri->first()->file_gambar) : asset('frontend/images/destination-1.jpg') }}');">
                                <span class="price">Rp
                                    {{ number_format($paket->harga_mulai_from, 0, ',', '.') }}/pax</span>
                            </a>
                            <div class="text p-4">
                                <span class="days"><i class="fa fa-clock-o mr-1"></i>{{ $paket->durasi }}</span>
                                <h3><a
                                        href="{{ route('frontend.paket.show', $paket->slug) }}">{{ $paket->nama_paket }}</a>
                                </h3>
                                <p class="location">
                                    <span class="fa fa-map-marker"></span>
                                    @foreach ($paket->destinasi->take(2) as $dest)
                                        {{ $dest->nama_destinasi }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if ($paket->destinasi->count() > 2)
                                        <small class="text-muted">+{{ $paket->destinasi->count() - 2 }} lainnya</small>
                                    @endif
                                </p>
                                <ul>
                                    <li><span class="flaticon-shower"></span>Include Transport</li>
                                    <li><span class="flaticon-king-size"></span>Hotel/Resto</li>
                                    <li><span class="flaticon-sun-umbrella"></span>Tiket Masuk</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 text-center text-muted py-5">
                        <p>Belum ada paket wisata populer yang dipublikasikan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- B2B Call To Action Section -->
    <section class="ftco-section ftco-intro ftco-no-pt">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <div class="img" style="background-image: url('{{ asset('frontend/images/bg_2.jpg') }}');">
                        <div class="overlay"></div>
                        <h2>Khusus Panitia Tour & Sekolah</h2>
                        <p>Tim kami dapat menerbitkan draf proposal resmi lengkap dengan breakdown biaya fixed dan variable
                            sesuai budget per peserta.</p>
                        <p class="mb-0">
                            <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20panitia%20rombongan%20minta%20dibuatkan%20proposal%20perjalanan"
                                target="_blank" class="btn btn-primary px-4 py-3">
                                <i class="fa fa-whatsapp mr-1"></i> Minta Dibuatkan Proposal
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Documentation Preview -->
    @if ($galeriTerbaru->count() > 0)
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center pb-4">
                    <div class="col-md-12 heading-section text-center ftco-animate">
                        <span class="subheading">Dokumentasi</span>
                        <h2 class="mb-4">Galeri Perjalanan Rombongan</h2>
                    </div>
                </div>
                <div class="row d-flex">
                    @foreach ($galeriTerbaru as $galeri)
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="blog-entry justify-content-end w-100">
                                <!-- Ubah href mengarah ke URL gambar dan tambahkan class image-popup -->
                                <a href="{{ asset('storage/' . $galeri->file_gambar) }}"
                                    class="block-20 image-popup d-flex align-items-center justify-content-center"
                                    style="background-image: url('{{ asset('storage/' . $galeri->file_gambar) }}');">
                                    <span class="fa fa-search text-white fa-2x opacity-0 hover-opacity-100"
                                        style="transition: 0.3s;"></span>
                                </a>
                                <div class="text">
                                    <h3 class="heading">
                                        <!-- Judul juga membuka pop-up gambar -->
                                        <a href="{{ asset('storage/' . $galeri->file_gambar) }}" class="image-popup">
                                            {{ $galeri->judul }}
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- <div class="row mt-4">
                    <div class="col text-center">
                        <a href="{{ route('frontend.galeri') }}" class="btn btn-outline-primary py-3 px-4">Lihat Seluruh
                            Galeri &rarr;</a>
                    </div>
                </div> --}}
            </div>
        </section>
    @endif

    <!-- Footer Call To Action -->
    <section class="ftco-section bg-light py-5">
        <div class="container text-center">
            <h3 class="font-weight-bold mb-3">Siap Merencanakan Liburan Rombongan Anda?</h3>
            <p class="text-muted mb-4">Hubungi customer service kami yang siap membantu melayani reservasi bus, tiket
                tempat wisata, dan susunan acara tour.</p>
            <a href="{{ route('frontend.kontak') }}" class="btn btn-primary py-3 px-5">
                <i class="fa fa-phone mr-1"></i> Hubungi Layanan Customer
            </a>
        </div>
    </section>
@endsection
