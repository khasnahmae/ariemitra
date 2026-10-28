<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'proposal.PaketWisata'])->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function sendInvoice($uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        $booking->status = 'sent';
        $booking->save();

        return back()->with('success', 'Invoice berhasil diterbitkan dan dikirim ke Client!');
    }

    // CETAK INVOICE / PERINTAH BAYAR AWAL
    public function cetakInvoice($uuid)
    {
        $booking = Booking::with(['proposal.PaketWisata', 'proposal.proposalArmada.armada'])->where('uuid', $uuid)->firstOrFail();
        return view('admin.booking.cetak_invoice', compact('booking'));
    }

    // CETAK BUKTI SEWA / KUITANSI RESMI (ADA RIWAYAT PEMBAYARAN)
    public function cetakBuktiSewa($uuid)
    {
        $booking = Booking::with(['proposal.PaketWisata', 'proposal.proposalArmada.armada'])->where('uuid', $uuid)->firstOrFail();
        return view('admin.booking.cetak_bukti_sewa', compact('booking'));
    }

    // VERIFIKASI / INPUT PEMBAYARAN (TUNAI ATALAU TRANSFER)
    public function konfirmasiTunai(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        $nominalBayar = (float) $request->jumlah_bayar;

        // Jika belum ada DP 1, masukkan ke DP 1. Jika sudah ada, masukkan ke DP 2
        if ($booking->dp_1 == 0) {
            $booking->dp_1 = $nominalBayar;
        } else {
            $booking->dp_2 += $nominalBayar;
        }

        // Hitung total akumulasi pembayaran
        $totalBayar = $booking->dp_1 + $booking->dp_2;
        $booking->sisa_pembayaran = max(0, $booking->biaya_sewa - $totalBayar);

        // Update status transaksi berdasarkan sisa pembayaran
        if ($booking->sisa_pembayaran <= 0) {
            $booking->status = 'lunas';
        } else {
            $booking->status = 'booked';
        }

        $booking->save();

        return back()->with('success', 'Pembayaran berhasil verifikasi & dicatat!');
    }
}
