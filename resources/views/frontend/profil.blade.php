@extends('layouts.app')

@section('content')
    <!-- HERO / BREADCRUMB HEADER -->
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('{{ asset('images/img7.png') }}')">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-center">
                <div class="col-md-9 ftco-animate pb-5 text-center">
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('frontend.home') }}">Beranda <i
                                    class="fa fa-chevron-right"></i></a></span>
                        <span>Tentang Kami <i class="fa fa-chevron-right"></i></span>
                    </p>
                    <h1 class="mb-0 bread">Profil Perusahaan</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES & WELCOME SECTION -->
    <section class="ftco-section services-section">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-6 order-md-last heading-section pl-md-5 ftco-animate d-flex align-items-center">
                    <div class="w-100">
                        <span class="subheading">Selamat Datang di Biro Perjalanan Kami</span>
                        <h2 class="mb-4">Solusi Perjalanan Wisata Terpercaya & Transparan</h2>
                        <p>Kami adalah agen biro perjalanan profesional yang berdedikasi memberikan pengalaman liburan
                            terbaik bagi instansi, sekolah, maupun rombongan keluarga.</p>
                        <p>Dengan dukungan sistem perhitungan proposal otomatis, kami menjamin transparansi biaya HTM, rute
                            tol, serta akomodasi tanpa ada biaya tersembunyi demi kenyamanan perjalanan Anda.</p>
                        <p><a href="{{ route('frontend.paket.index') }}" class="btn btn-primary py-3 px-4">Cari Paket
                                Wisata</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-1 d-block img"
                                style="background-image: url('{{ asset('frontend/images/services-1.jpg') }}');">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="flaticon-paragliding"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Transparansi Biaya</h3>
                                    <p>Sistem perhitungan proposal kami menjamin rincian biaya yang akurat dan terbuka.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-2 d-block img"
                                style="background-image: url('{{ asset('frontend/images/services-2.jpg') }}');">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="flaticon-route"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Armada Terawat</h3>
                                    <p>Didukung oleh armada bus pariwisata berfasilitas lengkap & pengemudi handal.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-3 d-block img"
                                style="background-image: url('{{ asset('frontend/images/services-3.jpg') }}');">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="flaticon-tour-guide"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Tour Leader</h3>
                                    <p>Tim pramuwisata ramah yang siap mendampingi kebutuhan rombongan sepanjang tour.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 d-flex align-self-stretch ftco-animate">
                            <div class="services services-1 color-4 d-block img"
                                style="background-image: url('{{ asset('frontend/images/services-4.jpg') }}');">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="flaticon-map"></span></div>
                                <div class="media-body">
                                    <h3 class="heading mb-3">Rute Fleksibel</h3>
                                    <p>Kustomisasi tujuan wisata dan agenda perjalanan sesuai dengan keinginan Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEO BANNER SECTION -->
    <section class="ftco-section ftco-about img" style="background-image: url('{{ asset('frontend/images/bg_4.jpg') }}');">
        <div class="overlay"></div>
        <div class="container py-md-5">
            <div class="row py-md-5">
                <div class="col-md d-flex align-items-center justify-content-center">
                    <a href="https://vimeo.com/45830194"
                        class="icon-video popup-vimeo d-flex align-items-center justify-content-center mb-4">
                        <span class="fa fa-play"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT INTRO SECTION -->
    <section class="ftco-section ftco-about ftco-no-pt img">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-12 about-intro">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div class="img d-flex w-100 align-items-center justify-content-center"
                                style="background-image:url('{{ asset('frontend/images/about-1.jpg') }}');">
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-5 py-5">
                            <div class="row justify-content-start pb-3">
                                <div class="col-md-12 heading-section ftco-animate">
                                    <span class="subheading">Tentang Kami</span>
                                    <h2 class="mb-4">Buat Perjalanan Anda Berkesan dan Aman Bersama Kami</h2>
                                    <p>Kami mengutamakan keselamatan, ketepatan waktu, serta kenyamanan peserta dari awal
                                        hingga kembali ke titik asal. Percayakan agenda liburan instansi atau sekolah Anda
                                        kepada tim profesional kami.</p>
                                    <p><a href="{{ route('frontend.kontak') }}" class="btn btn-primary">Konsultasikan Rute
                                            Anda</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
