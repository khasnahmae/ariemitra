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

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #0F172A;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        .header {
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: #0F172A 0.5px solid;
        }

        .brand-name {
            font-size: 20px;
            font-weight: bold;
            color: #1E3A8A;
        }

        .company-info {
            font-size: 10px;
            color: #64748B;
            margin-top: 4px;
        }

        .proposal-badge {
            text-align: right;
        }

        .proposal-code {
            font-size: 10px;
            color: #64748B;
            font-weight: bold;
        }

        .logo {
            max-width: 95px;
            max-height: 42px;
        }

        .section-header {
            font-size: 10px;
            font-weight: bold;
            color: #0F172A;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .client-block,
        .dest-block,
        .facility-block {
            margin-bottom: 22px;
        }

        .client-table,
        .dest-table {
            width: 100%;
        }

        .client-table td,
        .dest-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .meta-label {
            font-size: 9px;
            color: #0F172A;
            font-weight: normal;
            width: 25%;
        }

        .meta-separator {
            width: 3%;
            font-weight: bold;
        }

        .meta-value {
            font-size: 10px;
            font-weight: bold;
            color: #0F172A;
            width: 72%;
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

        .price-container {
            margin-top: 24px;
            margin-bottom: 24px;
        }

        .price-amount {
            font-size: 18px;
            color: #1E3A8A;
            font-weight: bold;
        }

        .signature-wrapper {
            margin-top: 28px;
        }
    </style>
</head>

<body>

    @php
        $destinations = $proposal->proposalDestinasi ?? collect();
        $facilities = [];
        if ($proposal->fasilitas) {
            $facilities = array_filter(array_map('trim', explode("\n", str_replace("\r", '', $proposal->fasilitas))));
        }
    @endphp

    <!-- HEADER KOP -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 62%; vertical-align: middle;">
                    <div class="brand-name">Arie Mitra Tour & Travel</div>
                    <div class="company-info">
                        Jl. Perintis Kemerdekaan No.20 Taman, Pemalang (Depan Texmaco)<br>
                        Telp/WA: 0877 2208 2751 &nbsp;|&nbsp; Email: selfianamiftahuljanah@gmail.com
                    </div>
                </td>
                <td style="width: 38%; vertical-align: middle;" class="proposal-badge">
                    @if (file_exists(public_path('images/logo.png')))
                        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
                    @endif
                    <div class="proposal-code">{{ $proposal->kode_proposal }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- INFORMASI PERJALANAN -->
    <div class="client-block">
        <div class="section-header">Informasi Rencana Perjalanan</div>
        <table class="client-table">
            <tr>
                <td class="meta-label">Durasi Perjalanan</td>
                <td class="meta-separator">:</td>
                <td class="meta-value">
                    {{ $proposal->jumlah_hari }} Hari {{ $proposal->jumlah_malam }} Malam
                    ({{ $proposal->is_menginap === 'ya' ? 'Menginap' : 'Tidak Menginap' }})
                </td>
            </tr>
            <tr>
                <td class="meta-label">Jumlah Peserta</td>
                <td class="meta-separator">:</td>
                <td class="meta-value">{{ $proposal->jumlah_peserta }} Pax</td>
            </tr>
        </table>
    </div>

    <!-- DESTINASI WISATA -->
    <div class="dest-block">
        <div class="section-header">Destinasi Wisata Yang Dikunjungi</div>
        <table class="dest-table">
            <tbody>
                @forelse ($destinations as $index => $pd)
                    <tr>
                        <td class="meta-label">
                            <span style="margin-right: 4px;">{{ $index + 1 }}.</span>
                            {{ $pd->destinasi->nama_destinasi ?? 'Objek Wisata' }} ({{ $pd->destinasi->lokasi ?? '' }})
                        </td>
                        <td class="meta-separator">:</td>
                        <td class="meta-value">
                            Rp {{ number_format($pd->htm_snapshot, 0, ',', '.') }} / pax
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="color: #64748B;">Belum ada destinasi terpilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- FASILITAS -->
    <div class="facility-block">
        <div class="section-header">Fasilitas Termasuk (All-In)</div>
        <div class="facility-list">
            @forelse ($facilities as $facility)
                <div class="facility-item">
                    <span class="bullet-dot">&#10003;</span> {{ $facility }}
                </div>
            @empty
                <div style="color: #64748B;">Fasilitas belum ditentukan.</div>
            @endforelse
        </div>
    </div>

    <!-- RINGKASAN HARGA -->
    <div class="price-container">
        <div style="font-size: 10px; color: #64748B;">Harga Penawaran Final / Pax untuk {{ $proposal->jumlah_peserta }}
            Pax:</div>
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
                    <div style="font-size: 10px; color: #64748B;">
                        Pemalang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </div>
                    <div style="font-size: 10px; font-weight: bold; color: #0F172A; margin-top: 2px;">
                        Arie Mitra Tour & Travel
                    </div>
                    <div style="height: 60px;"></div>
                    <div style="font-size: 10px; font-weight: bold; text-decoration: underline;">
                        {{ $proposal->user->nama ?? 'Arie Mitra Management' }}
                    </div>
                    <div style="font-size: 10px; color: #64748B;">Tour Planner & Operation Manager</div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
