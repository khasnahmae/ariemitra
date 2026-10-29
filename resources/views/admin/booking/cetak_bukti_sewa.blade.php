<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>BUKTI SEWA - {{ $booking->no_invoice }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 30px;
            color: #000;
            position: relative;
            /* Diperlukan agar stempel bisa diposisikan terhadap body */
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .logo-title {
            font-size: 20px;
            font-weight: bold;
            color: #1A2171;
            text-transform: uppercase;
        }

        .sub-header {
            font-size: 13px;
            margin-top: -3px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
        }

        table.content {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.content td {
            padding: 5px 0;
            vertical-align: top;
        }

        .riwayat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }

        .riwayat-table th,
        .riwayat-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .footer-table {
            width: 100%;
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
        }

        /* STYLES UNTUK STEMPEL LUNAS */
        .stamp-lunas {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            color: rgba(220, 53, 69, 0.45);
            /* Warna merah dengan transparansi overlay */
            border: 5px double rgba(220, 53, 69, 0.45);
            font-size: 52px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            text-transform: uppercase;
            padding: 10px 30px;
            letter-spacing: 6px;
            border-radius: 12px;
            pointer-events: none;
            z-index: 9999;
            user-select: none;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak Dokumen</button>
    </div>

    <!-- STEMPEL WATERMARK LUNAS (Tampil Hanya Jika Status Lunas atau Sisa Bayar <= 0) -->
    @if (strtolower($booking->status) == 'lunas' || $booking->sisa_pembayaran <= 0)
        <div class="stamp-lunas">L U N A S</div>
    @endif

    <div class="header">
        <div style="font-size: 13px; font-weight: bold;">BIRO PERJALANAN TOUR & TRAVEL</div>
        <div class="logo-title">ARIE MITRA SEJATI</div>
        <div class="sub-header">Jl. Perintis Kemerdekaan No. 20 Taman - Pemalang (Depan Texmaco)</div>
        <div class="sub-header">HP. 0823 2400 8886 Fax. (0284) 5859370</div>
    </div>

    <div class="title">BUKTI SEWA KENDARAAN / PAKET WISATA</div>

    <table class="content">
        <tr>
            <td width="25%">Kepada Yth.</td>
            <td width="2%">:</td>
            <td width="43%"><strong>{{ $booking->nama_pemesan }}</strong></td>
            <td width="10%">No. Inv</td>
            <td width="20%">: {{ $booking->no_invoice }}</td>
        </tr>
        <tr>
            <td>Alamat Penjemputan</td>
            <td>:</td>
            <td>{{ $booking->alamat_penjemputan }}</td>
            <td>Telp</td>
            <td>: {{ $booking->telepon_pemesan }}</td>
        </tr>
        <tr>
            <td>Guna Membayar</td>
            <td>:</td>
            <td colspan="3">Pemesanan Paket Wisata {{ $booking->tujuan_wisata }}</td>
        </tr>
        <tr>
            <td>Hari / Tgl Keberangkatan</td>
            <td>:</td>
            <td colspan="3">{{ \Carbon\Carbon::parse($booking->tanggal_keberangkatan)->isoFormat('dddd, D MMMM Y') }}
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-top: 15px;">Riwayat Pembayaran:</div>
    <table class="riwayat-table">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th>Keterangan</th>
                <th>Metode Pembayaran</th>
                <th>Jumlah Masuk</th>
            </tr>
        </thead>
        <tbody>
            @if ($booking->dp_1 > 0)
                <tr>
                    <td>Pembayaran DP I (Uang Muka)</td>
                    <td>{{ strtoupper($booking->metode_dp_1 ?? ($booking->metode_pembayaran ?? 'TUNAI')) }}</td>
                    <td>Rp {{ number_format($booking->dp_1, 0, ',', '.') }}</td>
                </tr>
            @endif

            @if ($booking->dp_2 > 0 && in_array($booking->status, ['lunas', 'booked']))
                <tr>
                    <td>Pembayaran Pelunasan / DP II</td>
                    <td>{{ strtoupper($booking->metode_dp_2 ?? ($booking->metode_pembayaran ?? 'TRANSFER')) }}</td>
                    <td>Rp {{ number_format($booking->dp_2, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="content" style="margin-top: 15px;">
        <tr>
            <td width="30%">Total Biaya Sewa</td>
            <td width="2%">:</td>
            <td><strong>Rp {{ number_format($booking->biaya_sewa, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Total Sudah Dibayar</td>
            <td>:</td>
            <td style="color: green;"><strong>Rp
                    {{ number_format($booking->dp_1 + $booking->dp_2, 0, ',', '.') }}</strong></td>
        </tr>

        <!-- LOGIKA: HANYA TAMPILKAN SISA PEMBAYARAN JIKA BELUM LUNAS -->
        @if (strtolower($booking->status) != 'lunas' && $booking->sisa_pembayaran > 0)
            <tr>
                <td>Sisa Pembayaran Tagihan</td>
                <td>:</td>
                <td style="color: red;"><strong>Rp {{ number_format($booking->sisa_pembayaran, 0, ',', '.') }}</strong>
                </td>
            </tr>
        @endif

        <tr>
            <td>Status Transaksi</td>
            <td>:</td>
            <td><strong>{{ strtoupper($booking->status) }}</strong></td>
        </tr>
    </table>

    <table class="footer-table">
        <tr>
            <td width="50%">Penyewa / Client</td>
            <td width="50%">
                Pemalang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                BIRO PERJALANAN<br>
                <strong>ARIE MITRA SEJATI</strong>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 50px;">( {{ $booking->nama_pemesan }} )</td>
            <td style="padding-top: 50px;">( Admin / Petugas )</td>
        </tr>
    </table>
</body>

</html>
