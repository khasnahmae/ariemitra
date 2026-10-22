@extends('layouts.app')

@section('content')
    <style>
        .bento-gallery {
            display: grid;
            gap: 12px;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .bento-item {
            position: relative;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .bento-item:hover {
            transform: scale(1.01);
            filter: brightness(0.95);
        }

        .bento-item .overlay-more {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            backdrop-filter: blur(2px);
        }

        /* Layout Grid Variants */
        .bento-grid-1 {
            grid-template-columns: 1fr;
            height: 420px;
        }

        .bento-grid-2 {
            grid-template-columns: 1fr 1fr;
            height: 400px;
        }

        .bento-grid-3 {
            grid-template-columns: 1.2fr 0.8fr;
            grid-template-rows: 1fr 1fr;
            height: 420px;
        }

        .bento-grid-3 .bento-item-0 {
            grid-row: span 2;
        }

        .bento-grid-4 {
            grid-template-columns: 1.2fr 0.8fr;
            grid-template-rows: repeat(3, 1fr);
            height: 450px;
        }

        .bento-grid-4 .bento-item-0 {
            grid-row: span 3;
        }

        .bento-grid-5 {
            grid-template-columns: 1.2fr 0.8fr 0.8fr;
            grid-template-rows: 1fr 1fr;
            height: 450px;
        }

        .bento-grid-5 .bento-item-0 {
            grid-row: span 2;
        }

        @media (max-width: 768px) {
            .bento-gallery {
                display: flex !important;
                flex-direction: column;
                height: auto !important;
            }

            .bento-item {
                height: 220px !important;
            }
        }

        /* Nav Tabs Styling */
        .nav-tabs-custom {
            border-bottom: 1px solid #e9ecef;
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            overflow-x: auto;
            white-space: nowrap;
        }

        .nav-tabs-custom .nav-link-item {
            color: #6c757d;
            font-weight: 600;
            padding-bottom: 0.75rem;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        .nav-tabs-custom .nav-link-item.active,
        .nav-tabs-custom .nav-link-item:hover {
            color: #111;
            border-bottom-color: #007bff;
        }

        /* Sidebar Box */
        .sidebar-booking-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 1.5rem;
            position: sticky;
            top: 100px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
    </style>

    @php
        $headerBg = asset('frontend/images/bg_1.jpg');

        // Kumpulkan semua foto destinasi ke dalam array
        $fotoDestinasiList = [];
        foreach ($paket->proposalDestinasi as $pDest) {
            if ($pDest->destinasi && $pDest->destinasi->foto) {
                $url = \Illuminate\Support\Str::startsWith($pDest->destinasi->foto, ['http://', 'https://'])
                    ? $pDest->destinasi->foto
                    : asset('storage/' . $pDest->destinasi->foto);
                $fotoDestinasiList[] = [
                    'url' => $url,
                    'nama' => $pDest->destinasi->nama_destinasi,
                ];
            }
        }

        // Gambar Fallback jika tidak ada foto destinasi sama sekali
        if (empty($fotoDestinasiList)) {
            $fotoDestinasiList[] = [
                'url' => asset('frontend/images/destination-1.jpg'),
                'nama' => $paket->PaketWisata->nama_paket ?? 'Destinasi',
            ];
        }

        $totalFoto = count($fotoDestinasiList);
        $gridClass = 'bento-grid-' . min($totalFoto, 5);
    @endphp

    <!-- DETAIL CONTENT SECTION -->
    <section class="ftco-section bg-white pt-5 space">
        <div class="container">

            <!-- HEADER TITLE & BADGE -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
                <div>
                    <h2 class="font-weight-bold text-dark mb-1">
                        {{ $paket->PaketWisata->nama_paket ?? 'Paket Wisata' }}, Sub-Paket {{ $paket->nama_sub_paket }}
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fa fa-map-marker text-primary mr-1"></i>
                        @foreach ($paket->proposalDestinasi->take(3) as $pDest)
                            {{ $pDest->destinasi->nama_destinasi ?? '' }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge badge-secondary px-3 py-2 font-weight-normal" style="font-size: 0.9rem;">
                        <i class="fa fa-clock-o mr-1"></i>
                        {{ $paket->jumlah_hari }} Hari
                        @if ($paket->jumlah_malam > 0)
                            {{ $paket->jumlah_malam }} Malam
                        @endif
                    </span>
                </div>
            </div>

            <!-- BENTO GRID GALLERY -->
            <div class="bento-gallery {{ $gridClass }}">
                @php
                    $displayLimit = min($totalFoto, 5);
                @endphp
                @for ($i = 0; $i < $displayLimit; $i++)
                    @php
                        $foto = $fotoDestinasiList[$i];
                        $isLast = $i === $displayLimit - 1 && $totalFoto > $displayLimit;
                        $remaining = $totalFoto - $displayLimit;
                    @endphp
                    <div class="bento-item bento-item-{{ $i }}"
                        style="background-image: url('{{ $foto['url'] }}');" title="{{ $foto['nama'] }}">
                        @if ($isLast)
                            <div class="overlay-more">
                                +{{ $remaining }}
                            </div>
                        @endif
                    </div>
                @endfor
            </div>

            <div class="row">
                <!-- KOLOM KIRI: DETAIL CONTENT -->
                <div class="col-lg-8">

                    <!-- DESTINASI YANG DIKUNJUNGI -->
                    <div id="destinasi" class="mb-5">
                        <h4 class="font-weight-bold text-dark mb-3">Destinasi Wisata Yang Dikunjungi</h4>
                        <div class="row">
                            @foreach ($paket->proposalDestinasi as $pDest)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 font-weight-bold text-secondary">
                                                {{ $pDest->destinasi->nama_destinasi ?? '-' }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- FASILITAS & LAYANAN -->
                    <div id="fasilitas" class="mb-5">
                        <h4 class="font-weight-bold text-dark mb-3">Fasilitas & Layanan</h4>
                        <div class="p-4 bg-light rounded text-dark border" style="white-space: pre-line; line-height: 1.8;">
                            {{ $paket->fasilitas ?? 'Fasilitas menyesuaikan standar layanan tour.' }}
                        </div>
                    </div>

                    <!-- KETENTUAN PERJALANAN -->
                    <div id="ketentuan" class="mb-5">
                        <h4 class="font-weight-bold text-dark mb-3">Syarat & Ketentuan</h4>
                        <ul class="text-secondary pl-3" style="line-height: 1.8;">
                            <li>Harga sewaktu waktu dapat berubah mengikuti kebijakan tarif tol atau HTM objek wisata.</li>
                            <li>Total biaya penawaran akhir disesuaikan kembali dengan jumlah kuota peserta real dan opsi
                                kustomisasi rute.</li>
                            <li>Pemesanan dan konfirmasi jadwal dapat dilakukan langsung melalui layanan Customer Service WA
                                kami.</li>
                        </ul>
                    </div>

                </div>

                <!-- KOLOM KANAN: SIDEBAR BOOKING CARD -->
                <div class="col-lg-4">
                    <div class="sidebar-booking-card">
                        <span class="text-uppercase text-muted font-weight-bold small d-block mb-1">Mulai Dari</span>
                        <h2 class="text-success font-weight-bold mb-3">
                            Rp {{ number_format($paket->harga_akhir_per_pax, 0, ',', '.') }}
                            <small class="text-muted font-weight-normal" style="font-size: 0.85rem;">/ pax</small>
                        </h2>

                        <div class="p-3 bg-light rounded mb-4">
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted"><i class="fa fa-clock-o mr-1"></i> Durasi:</span>
                                <span class="font-weight-bold text-dark">
                                    {{ $paket->jumlah_hari }} Hari
                                    @if ($paket->jumlah_malam > 0)
                                        {{ $paket->jumlah_malam }} Malam
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted"><i class="fa fa-users mr-1"></i> Minimal Peserta:</span>
                                <span class="font-weight-bold text-dark">{{ $paket->jumlah_peserta }} Pax</span>
                            </div>
                        </div>

                        <ul class="list-unstyled text-secondary small mb-4">
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Tiket HTM Wisata Termasuk</li>
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Transportasi & Tol Termasuk
                            </li>
                            <li class="mb-2"><i class="fa fa-check text-success mr-2"></i> Tour Leader & Crew Service</li>
                        </ul>

                        <a href="{{ $waLink }}" target="_blank"
                            class="btn btn-success btn-block py-3 font-weight-bold shadow-sm rounded-lg">
                            <i class="fa fa-whatsapp fa-lg mr-2"></i> Minta Proposal / Order via WA
                        </a>

                        <a href="{{ route('frontend.paket.index') }}"
                            class="btn btn-outline-secondary btn-block mt-2 py-2 small">
                            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Catalog
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
