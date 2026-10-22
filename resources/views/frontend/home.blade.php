@extends('layouts.app')

@section('content')
    <!-- Hero Banner Section -->
    <div class="img" style="background-image: url('{{ asset('frontend/images/img1.png') }}');">
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
                                                                placeholder="Misal: Cirebon, Semarang, 1A..."
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
                                style="background-image: url('{{ asset('frontend/images/img5.png') }}');">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-bus"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Armada Executive</h3>
                                    <p>Format Seat 2-2, Full AC, Multimedia Karaoke, Audio Premium, serta Reclining Seat
                                        yang nyaman.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-4 d-block img"
                                style="background-image: url('{{ asset('frontend/images/img6.png') }}')">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-ticket"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Tiket & Konsumsi Berkualitas</h3>
                                    <p>Tiket masuk semua lokasi wisata terintegrasi tanpa antre, makan prasmanan/box
                                        higienis, snack perjalanan, dan air mineral.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-3 d-block img"
                                style="background-image: url('{{ asset('frontend/images/img8.png') }}')">
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
                                style="background-image: url('{{ asset('frontend/images/img7.png') }}')">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-calculator"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Biaya Transparan</h3>
                                    <p>Sistem kalkulasi biaya fixed & variable secara akurat untuk sekolah/instansi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Tour Packages Section -->
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center pb-4">
                <div class="col-md-12 heading-section text-center ftco-animate">
                    <span class="subheading">Pilihan Paket</span>
                    <h2 class="mb-4">Paket Wisata Populer</h2>
                </div>
            </div>
            <div class="row">
                @forelse($paketPopuler as $paket)
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
                                <span class="days">
                                    <i class="fa fa-clock-o mr-1"></i>
                                    {{ $paket->jumlah_hari }} Hari
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
                                <p class="location">
                                    <span class="fa fa-map-marker"></span>
                                    @foreach ($paket->proposalDestinasi->take(2) as $pDest)
                                        {{ $pDest->destinasi->nama_destinasi ?? '' }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if ($paket->proposalDestinasi->count() > 2)
                                        <small class="text-muted">+{{ $paket->proposalDestinasi->count() - 2 }}
                                            lainnya</small>
                                    @endif
                                </p>
                                <ul>
                                    <li><span class="fa fa-users text-info mr-1"></span> Min. {{ $paket->jumlah_peserta }}
                                        Pax</li>
                                    <li><span class="fa fa-check-circle text-success mr-1"></span> All-In</li>
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

    <!-- SECTION GALERI DOKUMENTASI (MODAL POP-UP) -->
    @if (isset($galeriTerbaru) && $galeriTerbaru->count() > 0)
        <section class="ftco-section bg-light">
            <div class="container">
                <div class="row justify-content-center pb-4">
                    <div class="col-md-12 heading-section text-center ftco-animate">
                        <span class="subheading">Galeri Dokumentasi</span>
                        <h2 class="mb-4">Momen Perjalanan Wisata</h2>
                    </div>
                </div>
                <div class="row">
                    @foreach ($galeriTerbaru as $galeri)
                        @php
                            $fotoUrl = \Illuminate\Support\Str::startsWith($galeri->file_gambar, [
                                'http://',
                                'https://',
                            ])
                                ? $galeri->file_gambar
                                : asset('storage/' . $galeri->file_gambar);
                        @endphp
                        <div class="col-md-4 ftco-animate mb-4">
                            <div class="project-wrap rounded overflow-hidden shadow-sm bg-white">
                                <a href="javascript:void(0)"
                                    class="img d-flex align-items-center justify-content-center position-relative"
                                    style="background-image: url('{{ $fotoUrl }}'); height: 240px; background-size: cover; background-position: center;"
                                    data-toggle="modal" data-target="#galeriModal{{ $galeri->id }}">
                                    <div class="overlay d-flex align-items-center justify-content-center">
                                        <span class="fa fa-search-plus text-white fa-2x"></span>
                                    </div>
                                </a>
                                <div class="text p-3 text-center">
                                    <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 1rem;">
                                        {{ $galeri->judul }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL POP-UP UNTUK DOKUMENTASI -->
                        <div class="modal fade" id="galeriModal{{ $galeri->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="galeriModalLabel{{ $galeri->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content border-0 bg-transparent">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title text-white font-weight-bold"
                                            id="galeriModalLabel{{ $galeri->id }}">{{ $galeri->judul }}</h5>
                                        <button type="button" class="close text-white opacity-100" data-dismiss="modal"
                                            aria-label="Close" style="font-size: 2rem;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center p-2">
                                        <img src="{{ $fotoUrl }}" alt="{{ $galeri->judul }}"
                                            class="img-fluid rounded shadow-lg"
                                            style="max-height: 80vh; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- B2B Call To Action Section -->
    <section class="ftco-section ftco-intro ftco-no-pt">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <div class="img" style="background-image: url('{{ asset('frontend/images/bg_2.jpg') }}');">
                        <div class="overlay"></div>
                        <h2>Khusus Panitia Tour & Sekolah</h2>
                        <p>Tim kami dapat menerbitkan draf proposal resmi sesuai budget per peserta.</p>
                        <p class="mb-0">
                            <a href="https://wa.me/6287722082751?text=Halo%20Admin,%20saya%20panitia%20rombongan%20minta%20dibuatkan%20proposal%20perjalanan"
                                target="_blank" class="btn btn-primary px-4 py-3">
                                <i class="fa fa-whatsapp mr-1"></i> Minta Dibuatkan Proposal
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
