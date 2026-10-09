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
                        <span>Kontak <i class="fa fa-chevron-right"></i></span>
                    </p>
                    <h1 class="mb-0 bread">Hubungi Kami</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT INFO CARDS -->
    <section class="ftco-section ftco-no-pb contact-section mb-4">
        <div class="container">
            <div class="row d-flex contact-info">
                <div class="col-md-3 d-flex">
                    <div class="align-self-stretch box p-4 text-center">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-map-marker"></span>
                        </div>
                        <h3 class="mb-2">Alamat Kantor</h3>
                        <p>Jl. Pemuda No. 123, Kota Pemalang, Jawa Tengah - Indonesia</p>
                    </div>
                </div>
                <div class="col-md-3 d-flex">
                    <div class="align-self-stretch box p-4 text-center">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-phone"></span>
                        </div>
                        <h3 class="mb-2">WhatsApp / Telepon</h3>
                        <p><a href="https://wa.me/6281234567890" target="_blank">+62 812-3456-7890</a></p>
                    </div>
                </div>
                <div class="col-md-3 d-flex">
                    <div class="align-self-stretch box p-4 text-center">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-paper-plane"></span>
                        </div>
                        <h3 class="mb-2">Email Resmi</h3>
                        <p><a href="mailto:info@biroperjalanan.com">info@biroperjalanan.com</a></p>
                    </div>
                </div>
                <div class="col-md-3 d-flex">
                    <div class="align-self-stretch box p-4 text-center">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-globe"></span>
                        </div>
                        <h3 class="mb-2">Jam Operasional</h3>
                        <p>Senin - Sabtu<br><small class="text-muted">08.00 - 17.00 WIB</small></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT FORM & MAP SECTION -->
    <section class="ftco-section contact-section ftco-no-pt">
        <div class="container">
            <div class="row block-9">
                <div class="col-md-6 order-md-last d-flex">
                    <form action="#" class="bg-light p-5 contact-form w-100">
                        <h4 class="mb-4 font-weight-bold">Kirim Pesan / Pertanyaan</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Nama Lengkap Anda">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Alamat Email">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Subjek / Rencana Perjalanan">
                        </div>
                        <div class="form-group">
                            <textarea name="" id="" cols="30" rows="7" class="form-control"
                                placeholder="Tuliskan detail pertanyaan atau rencana estimasi kuota peserta..."></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Kirim Pesan" class="btn btn-primary py-3 px-5">
                        </div>
                    </form>
                </div>

                <div class="col-md-6 d-flex">
                    <div
                        class="bg-light p-5 w-100 d-flex flex-column justify-content-center align-items-center text-center rounded border">
                        <span class="fa fa-comments-o fa-4x text-primary mb-3"></span>
                        <h3 class="font-weight-bold mb-3">Butuh Respon Cepat?</h3>
                        <p class="text-muted mb-4">Hubungi tim customer service kami melalui WhatsApp untuk konsultasi rute
                            dan kustomisasi estimasi anggaran secara langsung.</p>
                        <a href="https://wa.me/6281234567890" target="_blank"
                            class="btn btn-success py-3 px-4 font-weight-bold">
                            <i class="fa fa-whatsapp mr-2"></i> Chat Via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
