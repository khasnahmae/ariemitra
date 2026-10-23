<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>
        {{ request('type') == 'bukti_sewa' || in_array($booking->status, ['booked', 'lunas']) ? 'BUKTI SEWA' : 'INVOICE PERINTAH BAYAR' }}
        - {{ $booking->no_invoice }}</title>
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
            padding: 4px 0;
            vertical-align: top;
        }

        .bank-box {
            border: 1px dashed #000;
            padding: 10px;
            margin: 15px 0;
            background: #f9f9f9;
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
        <button onclick="window.print()">Cetak Dokumen</button>
    </div>

    <div class="header">
        <div style="font-size: 14px; font-weight: bold;">BIRO PERJALANAN TOUR & TRAVEL</div>
        <div class="logo-title">Arie Mitra Sejati</div>
        <div class="sub-header">Jl. Perintis Kemerdekaan No. 20 Taman - Pemalang (Depan Texmaco)</div>
        <div class="sub-header">HP. 0823 2400 8886 Fax. (0284) 5859370</div>
    </div>

    <div class="title">
        {{ request('type') == 'bukti_sewa' || in_array($booking->status, ['booked', 'lunas']) ? 'BUKTI SEWA KENDARAAN / PAKET WISATA' : 'INVOICE PERINTAH PEMBAYARAN' }}
    </div>

    <table class="content">
        <tr>
            <td width="25%">Kepada Yth.</td>
            <td width="2%">:</td>
            <td width="43%">{{ $booking->nama_pemesan }}</td>
            <td width="8%">No. Inv</td>
            <td width="22%">: {{ $booking->no_invoice }}</td>
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
            <td>Hari/Tanggal Keberangkatan</td>
            <td>:</td>
            <td colspan="3">{{ \Carbon\Carbon::parse($booking->tanggal_keberangkatan)->isoFormat('dddd, D MMMM Y') }}
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; margin-top: 15px;">Rincian Biaya</div>
    <table class="content">
        <tr>
            <td width="25%">Total Biaya Sewa</td>
            <td width="2%">:</td>
            <td>Rp {{ number_format($booking->biaya_sewa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Uang Muka I (DP)</td>
            <td>:</td>
            <td>Rp {{ number_format($booking->dp_1, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Sisa Pembayaran</td>
            <td>:</td>
            <td><strong>Rp {{ number_format($booking->sisa_pembayaran, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    @if (!in_array($booking->status, ['booked', 'lunas']))
        <!-- BOX INSTRUKSI TRANSFER JIKA MASIH PERINTAH BAYAR -->
        <div class="bank-box">
            <strong>Instruksi Pembayaran Transfer:</strong><br>
            Silakan melakukan pembayaran DP minimal <strong>Rp 2.000.000</strong> atau Pelunasan ke rekening resmi
            kami:<br>
            <strong>Bank BCA: 1234-5678-90</strong> a.n. Arie Mitra Sejati<br>
            <strong>Bank BRI: 0987-6543-21</strong> a.n. Arie Mitra Sejati
        </div>
    @endif

    <div class="perhatian">
        <strong>Ketentuan & Perhatian:</strong>
        <ol style="padding-left: 15px; margin-top: 5px;">
            <li>Pembayaran DP minimal Rp 2.000.000 sebagai komitmen pemesanan.</li>
            <li>Pelunasan wajib dilakukan maksimal 7 (tujuh) hari sebelum pemberangkatan.</li>
            <li>Pembatalan sepihak dikenakan biaya administrasi 10% dari total biaya sewa.</li>
        </ol>
    </div>

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
