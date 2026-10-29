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

        // Tandai seluruh pesanan telah dibuka oleh admin
        Booking::where('is_read', false)->update(['is_read' => true]);

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
            'jumlah_bayar' => 'required|numeric|min:100000',
        ]);

        $nominalDiterima = (float) $request->jumlah_bayar;

        // Alokasikan pembayaran ke DP 1 atau DP 2
        if ($booking->dp_1 == 0) {
            $booking->dp_1 = $nominalDiterima;
        } else {
            $booking->dp_2 += $nominalDiterima;
        }

        $booking->nominal_konfirmasi = 0; // Reset draf nominal konfirmasi
        $totalMasuk = (float)$booking->dp_1 + (float)$booking->dp_2;
        $booking->sisa_pembayaran = max(0, (float)$booking->biaya_sewa - $totalMasuk);

        // Update status sesuai kriteria pelunasan
        if ($booking->sisa_pembayaran <= 0) {
            $booking->status = 'lunas';
        } else {
            $booking->status = 'booked';
        }

        $booking->save();

        return back()->with('success', 'Pembayaran sebesar Rp ' . number_format($nominalDiterima, 0, ',', '.') . ' berhasil diverifikasi!');
    }
}
