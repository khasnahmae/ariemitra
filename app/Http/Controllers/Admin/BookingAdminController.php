<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function index()
    {
        try {
            $bookings = Booking::latest()->get();
        } catch (\Exception $e) {
            $bookings = collect();
        }

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

        if ($booking->dp_1 == 0) {
            $booking->dp_1 = $bayar;
        } else {
            $booking->dp_2 = $bayar;
        }

        $totalMasuk = (float)$booking->dp_1 + (float)$booking->dp_2;
        $booking->sisa_pembayaran = max(0, (float)$booking->biaya_sewa - $totalMasuk);

        if ($booking->sisa_pembayaran <= 0) {
            $booking->status = 'lunas';
        } else {
            $booking->status = 'booked';
        }

        $booking->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }
}
