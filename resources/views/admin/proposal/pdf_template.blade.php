<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Proposal Penawaran - {{ $proposal->kode_proposal }}</title>
    <style>
        @page {
            size: A4;
            margin: 30px 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #0F172A;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
        }

        table {
            border-collapse: collapse;
        }

        /* =====================================================
           HEADER KOP
        ===================================================== */
        .header {
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: #0F172A 0.5px solid;
        }

        .brand-name {
            font-size: 20px;
            font-weight: bold;
            color: #1E3A8A;
            letter-spacing: -0.3px;
        }

        .brand-subtitle {
            font-size: 10px;
            color: #64748B;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-top: 1px;
        }

        .company-info {
            font-size: 10px;
            color: #64748B;
            line-height: 1.4;
            margin-top: 4px;
        }

        .proposal-badge {
            text-align: right;
        }

        .proposal-title {
            font-size: 14px;
            font-weight: bold;
            color: #1E3A8A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .proposal-code {
            font-size: 10px;
            color: #64748B;
            font-weight: bold;
            margin-top: 2px;
        }

        .logo {
            max-width: 95px;
            max-height: 42px;
            margin-bottom: 4px;
        }

        /* =====================================================
           SECTION HEADINGS
        ===================================================== */
        .section-header {
            font-size: 10px;
            font-weight: bold;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        /* =====================================================
           INFORMASI KLIEN
        ===================================================== */
        .client-block {
            margin-bottom: 22px;
        }

        .client-table {
            width: 100%;
        }

        .client-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .meta-label {
            font-size: 8px;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: normal;
            width: 25%;
        }

        .meta-separator {
            width: 3%;
            color: #0F172A;
            font-weight: bold;
        }

        .meta-value {
            font-size: 10px;
            font-weight: bold;
            color: #0F172A;
            width: 72%;
        }

        /* =====================================================
           DESTINASI WISATA (STRUKTUR DISAMAKAN PERSIS DENGAN KLIEN)
        ===================================================== */
        .dest-block {
            margin-bottom: 22px;
        }

        .dest-table {
            width: 100%;
        }

        .dest-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .dest-label {
            font-size: 10px;
            font-weight: normal;
            color: #0F172A;
            width: 25%;
        }

        .dest-separator {
            width: 3%;
            color: #0F172A;
            font-weight: bold;
        }

        .dest-price {
            font-size: 10px;
            font-weight: bold;
            color: #0F172A;
            width: 72%;
        }

        /* =====================================================
           FACILITIES
        ===================================================== */
        .facility-block {
            margin-bottom: 22px;
        }

        .facility-item {
            padding: 3px 0;
            font-size: 10px;
            color: #0F172A;
        }

        .bullet-dot {
            color: #1E3A8A;
            font-weight: bold;
            margin-right: 6px;
        }

        /* =====================================================
           PRICE BANNER
        ===================================================== */
        .price-container {
            margin-top: 24px;
            margin-bottom: 24px;
        }

        .price-label-text {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0F172A;
            font-weight: normal;
        }

        .price-amount {
            font-size: 18px;
            color: #1E3A8A;
            font-weight: bold;
            margin-top: 2px;
        }

        .price-subtext {
            font-size: 10px;
            color: #64748B;
            margin-top: 2px;
        }

        /* =====================================================
           SIGNATURE
        ===================================================== */
        .signature-wrapper {
            margin-top: 28px;
        }

        .signature-date {
            font-size: 10px;
            color: #64748B;
        }

        .signature-company {
            font-size: 10px;
            font-weight: bold;
            color: #0F172A;
            margin-top: 2px;
        }

        .signature-space {
            height: 64px;
        }

        .signature-name {
            font-size: 10px;
            font-weight: bold;
            text-decoration: underline;
            color: #0F172A;
        }

        .signature-role {
            font-size: 10px;
            color: #64748B;
        }
    </style>
</head>

<body>

    @php
        $destinations = $proposal->proposalDestinasi ?? collect();
        $armada = $proposal->proposalArmada->first();

        $facilities = [];
        if ($proposal->fasilitas) {
            $facilities = array_filter(array_map('trim', explode("\n", str_replace("\r", '', $proposal->fasilitas))));
        }

        if (empty($facilities)) {
            if ($armada) {
                $facilities[] = 'Bus Pariwisata (' . ($armada->armada->jenis_armada ?? 'Unit') . ')';
            }
            if ($destinations->count() > 0) {
                $facilities[] = 'Tiket masuk destinasi wisata';
            }
            foreach ($proposal->proposalBiayaKomponen as $component) {
                if (!empty($component->nama_komponen)) {
                    $facilities[] = $component->nama_komponen;
                }
            }
        }

        $totalEstimasi = $proposal->harga_akhir_per_pax * $proposal->jumlah_peserta;
    @endphp

    <!-- HEADER KOP -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 62%; vertical-align: middle;">
                    <div class="brand-name">Arie Mitra Tour & Travel</div>
                    {{-- <div class="brand-subtitle">Biro Perjalanan Tour & Travel</div> --}}
                    <div class="company-info">
                        Jl. Perintis Kemerdekaan No.20 Taman, Pemalang (Depan Texmaco)<br>
                        Telp/WA: 0877 2208 2751 &nbsp;|&nbsp; Email:
                        selfianamiftahuljanah@gmail.com
                    </div>
                </td>
                <td style="width: 38%; vertical-align: middle;" class="proposal-badge">
                    @if (file_exists(public_path('images/logo.png')))
                        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
                    @endif
                    {{-- <div class="proposal-title">PROPOSAL TOUR</div> --}}
                    <div class="proposal-code">{{ $proposal->kode_proposal }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- INFORMASI KLIEN & PERJALANAN -->
    <div class="client-block">
        <div class="section-header">Informasi Klien & Perjalanan</div>
        <table class="client-table">
            <tr>
                <td class="meta-label">Nama Klien / Instansi</td>
                <td class="meta-separator">:</td>
                <td class="meta-value">{{ $proposal->nama_klien }}</td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Proposal</td>
                <td class="meta-separator">:</td>
                <td class="meta-value">
                    {{ \Carbon\Carbon::parse($proposal->tanggal_proposal)->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td class="meta-label">Jumlah Peserta</td>
                <td class="meta-separator">:</td>
                <td class="meta-value">{{ $proposal->jumlah_peserta }} Pax</td>
            </tr>
        </table>
    </div>

    <!-- DESTINASI WISATA (PRESISI DAN SEJAJAR DENGAN INFORMASI KLIEN) -->
    <div class="dest-block">
        <div class="section-header">Destinasi Wisata</div>
        <table class="dest-table">
            <tbody>
                @forelse ($destinations as $index => $pd)
                    <tr>
                        <td class="dest-label">
                            <span style="margin-right: 4px;">{{ $index + 1 }}.</span>
                            {{ $pd->destinasi->nama_destinasi ?? 'Objek Wisata' }}
                        </td>
                        <td class="dest-separator"></td>
                        <td class="dest-price">
                            @if (isset($pd->htm_snapshot))
                                Rp {{ number_format($pd->htm_snapshot, 0, ',', '.') }}
                            @elseif(isset($pd->destinasi->htm_per_orang))
                                Rp {{ number_format($pd->destinasi->htm_per_orang, 0, ',', '.') }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="color: #64748B; padding: 4px 0;">Belum ada destinasi terpilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- FASILITAS -->
    <div class="facility-block">
        <div class="section-header">Fasilitas</div>
        <div class="facility-list">
            @forelse ($facilities as $facility)
                <div class="facility-item">
                    <span class="bullet-dot">&#10003;</span> {{ $facility }}
                </div>
            @empty
                <div style="color: #64748B; padding: 4px 0;">Fasilitas belum ditentukan.</div>
            @endforelse
        </div>
    </div>

    <!-- RINGKASAN HARGA -->
    <div class="price-container">
        {{-- <div class="price-label-text">Harga Penawaran / Pax</div> --}}
        <div class="price-subtext">Harga / pax (All-In) untuk {{ $proposal->jumlah_peserta }} Pax</div>
        <div class="price-amount">
            Rp {{ number_format($proposal->harga_akhir_per_pax, 0, ',', '.') }}
        </div>
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-wrapper">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%; text-align: center;">
                    <div class="signature-date">
                        Pemalang, {{ \Carbon\Carbon::parse($proposal->tanggal_proposal)->translatedFormat('d F Y') }}
                    </div>
                    <div class="signature-company">Arie Mitra Tour & Travel</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $proposal->user->name ?? 'Arie Mitra Management' }}</div>
                    <div class="signature-role">Tour Planner & Operation Manager</div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
