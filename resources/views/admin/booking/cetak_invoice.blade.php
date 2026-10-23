<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Sewa - {{ $booking->no_invoice }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 30px;
            color: #000;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .logo-title {
            font-size: 22px;
            font-weight: bold;
            color: #b30000;
            text-transform: uppercase;
        }

        .sub-header {
            font-size: 13px;
            margin-top: -5px;
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
            padding: 4px 0;
            vertical-align: top;
        }

        .perhatian {
            font-size: 11px;
            margin-top: 15px;
        }

        .footer-table {
            width: 100%;
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
        }

        .banner-bottom {
            border: 2px solid #000;
            text-align: center;
            font-weight: bold;
            padding: 5px;
            margin-top: 25px;
            font-size: 14px;
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
        <button onclick="window.print()">Cetak Faktur</button>
    </div>

    <div class="header">
        <div style="font-size: 14px; font-weight: bold;">BIRO PERJALANAN TOUR & TRAVEL</div>
        <div class="logo-title">Arie Mitra Sejati</div>
        <div class="sub-header">Jl. Perintis Kemerdekaan No. 20 Taman - Pemalang (Depan Texmaco)</div>
        <div class="sub-header">HP. 0823 2400 8886 Fax. (0284) 5859370</div>
    </div>

    <div class="title">BUKTI SEWA KENDARAAN / PAKET WISATA</div>

    <table class="content">
        <tr>
            <td width="25%">Telah terima dari</td>
            <td width="2%">:</td>
            <td width="43%">{{ $booking->nama_pemesan }}</td>
            <td width="8%">Telp</td>
            <td width="22%">: {{ $booking->telepon_pemesan }}</td>
        </tr>
        <tr>
            <td>Alamat Penjemputan</td>
            <td>:</td>
            <td colspan="3">{{ $booking->alamat_penjemputan }}</td>
        </tr>
        <tr>
            <td>Guna Membayar</td>
            <td>:</td>
            <td colspan="3">Persekot/Pelunasan Biaya Sewa {{ $booking->tujuan_wisata }}</td>
        </tr>
        <tr>
            <td>Hari/Tanggal</td>
            <td>:</td>
            <td colspan="3">{{ \Carbon\Carbon::parse($booking->tanggal_keberangkatan)->isoFormat('dddd, D MMMM Y') }}
            </td>
        </tr>
        <tr>
            <td>Jam Penjemputan</td>
            <td>:</td>
            <td colspan="3">{{ $booking->jam_penjemputan ?? '08.00 WIB' }}</td>
        </tr>
        <tr>
            <td>Tujuan</td>
            <td>:</td>
            <td colspan="3">{{ $booking->tujuan_wisata }}</td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-top: 15px;">Perincian</div>
    <table class="content">
        <tr>
            <td width="25%">Biaya Sewa</td>
            <td width="2%">:</td>
            <td>Rp {{ number_format($booking->biaya_sewa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Uang Muka I</td>
            <td>:</td>
            <td>Rp {{ number_format($booking->dp_1, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Uang Muka II</td>
            <td>:</td>
            <td>Rp {{ number_format($booking->dp_2, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Sisa</td>
            <td>:</td>
            <td><strong>Rp {{ number_format($booking->sisa_pembayaran, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="perhatian">
        <strong>Perhatian!</strong>
        <ol style="padding-left: 15px; margin-top: 5px;">
            <li>Penumpang agar tidak melebihi batas toleransi kapasitas tempat duduk.</li>
            <li>Mel, Tol, Parkir ditanggung oleh Panitia Rombongan.</li>
            <li>Keluar kota tujuan maksimal 20 km, lebih dari itu dikenakan biaya tambahan.</li>
            <li>Keterlambatan waktu akibat kelalaian rombongan dapat mengurangi tempat yang dituju.</li>
            <li>Pembatalan dikenai biaya administrasi 10% dari biaya sewa.</li>
            <li>Pelunasan minimal 7 (tujuh) hari, sebelum pemberangkatan.</li>
            <li>Apabila bus mengalami kerusakan (mogok), penyewa harus bersedia menunggu diganti dengan bus lain.</li>
            <li>Apabila Kru Bus kurang sopan santun segera memberikan informasi ke kantor pusat Telp. 0823 2400 8886.
            </li>
        </ol>
    </div>

    <table class="footer-table">
        <tr>
            <td width="50%">Penyewa</td>
            <td width="50%">
                Pemalang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                BIRO PERJALANAN<br>
                <strong>ARIE MITRA SEJATI</strong>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 60px;">( {{ $booking->nama_pemesan }} )</td>
            <td style="padding-top: 60px;">( Admin )</td>
        </tr>
    </table>

    <div class="banner-bottom">
        MELAYANI : STUDY TOUR, ZIARAH WALI, WISATA JAWA - BALI
    </div>

</body>

</html>
