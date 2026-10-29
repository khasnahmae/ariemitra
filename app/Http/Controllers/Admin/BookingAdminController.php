<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function index()
    {
        // Tandai seluruh booking sebagai telah dilihat oleh admin saat membuka daftar
        Booking::where('is_read_admin', false)->update(['is_read_admin' => true]);

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

    // VERIFIKASI PEMBAYARAN CLIENT / TERIMA TUNAI
    public function konfirmasiTunai(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1|max:' . $booking->biaya_sewa,
        ]);

        $nominalBayar = (float) $request->jumlah_bayar;

        // Tentukan alokasi pembayaran (DP 1 atau DP 2/Pelunasan)
        if ((float)$booking->dp_1 == 0) {
            $booking->dp_1 = $nominalBayar;
        } else {
            $booking->dp_2 += $nominalBayar;
        }

        // Akumulasi total pembayaran bersih
        $totalMasuk = (float)$booking->dp_1 + (float)$booking->dp_2;
        $booking->sisa_pembayaran = max(0, (float)$booking->biaya_sewa - $totalMasuk);
        $booking->nominal_pending = 0; // Reset nominal pending

        // Update status sesuai sisa tagihan
        if ($booking->sisa_pembayaran <= 0) {
            $booking->status = 'lunas';
        } else {
            $booking->status = 'booked';
        }

        $booking->is_read_admin = true;
        $booking->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi dan dicatat!');
    }
}
