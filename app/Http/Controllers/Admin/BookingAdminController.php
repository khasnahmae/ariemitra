<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function index()
    {
        // Panggilan tanpa relasi bermasalah agar tidak crash 500
        $bookings = Booking::with('user')->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function cetakInvoice($uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        return view('admin.booking.cetak_invoice', compact('booking'));
    }

    public function cetakBuktiSewa($uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();
        return view('admin.booking.cetak_bukti_sewa', compact('booking'));
    }

    public function verifikasiPembayaran(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
        ]);

        $bayar = (float) $request->jumlah_bayar;

        // Jika verifikasi pertama dan DP 1 belum terisi/masih 0
        if ($booking->dp_1 == 0) {
            $booking->dp_1 = $bayar;
        } elseif ($booking->dp_1 > 0 && $booking->status === 'pending_verification' && $booking->dp_2 == 0) {
            // Jika ini verifikasi untuk pembayaran pelunasan/DP 2
            $booking->dp_2 = $bayar;
        }

        // Kalkulasi total yang telah dibayar
        $totalMasuk = (float) $booking->dp_1 + (float) $booking->dp_2;
        $booking->sisa_pembayaran = max(0, (float) $booking->biaya_sewa - $totalMasuk);

        // Tentukan status transaksi
        if ($booking->sisa_pembayaran <= 0) {
            $booking->status = 'lunas';
        } else {
            $booking->status = 'booked';
        }

        $booking->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }
}
