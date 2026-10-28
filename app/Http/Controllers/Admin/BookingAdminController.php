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

    public function cetakInvoice($uuid)
    {
        $booking = Booking::with(['proposal.PaketWisata', 'proposal.proposalArmada.armada'])->where('uuid', $uuid)->firstOrFail();
        return view('admin.booking.cetak_invoice', compact('booking'));
    }

    public function cetakBuktiSewa($uuid)
    {
        $booking = Booking::with(['proposal.PaketWisata', 'proposal.proposalArmada.armada'])->where('uuid', $uuid)->firstOrFail();
        return view('admin.booking.cetak_bukti_sewa', compact('booking'));
    }

    public function konfirmasiTunai(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
        ]);

        $nominalBayar = (float) $request->jumlah_bayar;

        // Jika DP 1 belum tercatat / verifikasi awal
        if ($booking->dp_1 == 0) {
            $booking->dp_1 = $nominalBayar;
        } else if ($booking->dp_2 == 0) {
            $booking->dp_2 = $nominalBayar;
        } else {
            $booking->dp_2 += $nominalBayar;
        }

        // Kalkulasi ulang total bayar dan sisa tagihan
        $totalBayar = $booking->dp_1 + $booking->dp_2;
        $booking->sisa_pembayaran = max(0, $booking->biaya_sewa - $totalBayar);

        // Update status sesuai sisa tagihan
        if ($booking->sisa_pembayaran <= 0) {
            $booking->status = 'lunas';
        } else {
            $booking->status = 'booked';
        }

        $booking->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi & dicatat!');
    }
}
