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
            'jumlah_bayar' => 'required|numeric|min:1',
        ]);

        $nominalBayar = (float) $request->jumlah_bayar;

        // JIKA VERIFIKASI PERTAMA (Status masih pending_verification dan dp_2 masih 0)
        if ($booking->status === 'pending_verification' && (float)$booking->dp_2 == 0) {
            $booking->dp_1 = $nominalBayar;
        } else {
            // JIKA VERIFIKASI PELUNASAN / DP KEDUA
            $booking->dp_2 = $nominalBayar;
        }

        // HITUNG DENGAN CASTING FLOAT AGAR BEBAS ERROR MATH NULL
        $dp1 = (float) ($booking->dp_1 ?? 0);
        $dp2 = (float) ($booking->dp_2 ?? 0);
        $biayaSewa = (float) ($booking->biaya_sewa ?? 0);

        $totalMasuk = $dp1 + $dp2;
        $booking->sisa_pembayaran = max(0, $biayaSewa - $totalMasuk);

        // Tentukan Status Transaksi
        if ($booking->sisa_pembayaran <= 0) {
            $booking->status = 'lunas';
        } else {
            $booking->status = 'booked';
        }

        // Riset nominal pending jika ada kolomnya
        if (\Schema::hasColumn('bookings', 'nominal_pending')) {
            $booking->nominal_pending = 0;
        }

        if (\Schema::hasColumn('bookings', 'is_read_admin')) {
            $booking->is_read_admin = true;
        }

        $booking->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi dan dicatat!');
    }
}
