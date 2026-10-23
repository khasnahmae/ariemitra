<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request, $proposalUuid)
    {
        $proposal = Proposal::where('uuid', $proposalUuid)->firstOrFail();

        $request->validate([
            'alamat_penjemputan' => 'required|string',
            'tanggal_keberangkatan' => 'required|date|after_or_equal:today',
            'telepon_pemesan' => 'required|string|max:20',
        ]);

        $totalSewa = $proposal->harga_akhir_per_pax * $proposal->jumlah_peserta;
        $noInvoice = 'INV/' . date('Ymd') . '/' . rand(100, 999);

        Booking::create([
            'no_invoice' => $noInvoice,
            'user_id' => Auth::id(),
            'proposal_id' => $proposal->id,
            'nama_pemesan' => Auth::user()->nama,
            'telepon_pemesan' => $request->telepon_pemesan,
            'alamat_penjemputan' => $request->alamat_penjemputan,
            'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
            'tujuan_wisata' => $proposal->PaketWisata->nama_paket ?? 'Paket Wisata',
            'biaya_sewa' => $totalSewa,
            'sisa_pembayaran' => $totalSewa,
            'status' => 'pending',
        ]);

        return redirect()->route('client.riwayat')->with('success', 'Permintaan booking berhasil dikirim! Silakan tunggu konfirmasi invoice dari Admin.');
    }

    public function riwayat()
    {
        $bookings = Booking::with('proposal.PaketWisata')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('client.riwayat', compact('bookings'));
    }

    public function uploadPembayaran(Request $request, $uuid)
    {
        $booking = Booking::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,tunai',
            'jumlah_bayar' => 'required|numeric|min:2000000',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->metode_pembayaran === 'transfer') {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $booking->bukti_pembayaran = $path;
            $booking->metode_pembayaran = 'transfer';
        } else {
            $booking->metode_pembayaran = 'tunai';
            // Pilihan tunai di kantor tidak memerlukan unggah file
        }

        $booking->dp_1 = $request->jumlah_bayar;
        $booking->sisa_pembayaran = $booking->biaya_sewa - $request->jumlah_bayar;

        // Status diubah menjadi pending_verification agar Admin perlu memverifikasi terlebih dahulu
        $booking->status = 'pending_verification';
        $booking->save();

        return back()->with('success', 'Konfirmasi pembayaran berhasil dikirim! Silakan tunggu verifikasi dari Admin.');
    }
}
