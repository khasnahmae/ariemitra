<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Carbon\Carbon;

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

        $namaPaket = $proposal->PaketWisata->nama_paket ?? 'Paket Wisata';
        $subPaket = $proposal->nama_sub_paket ?? '';
        $tujuanWisata = $namaPaket . ($subPaket ? ' - ' . $subPaket : '');

        // PERIKSA BENTROK TANGGAL: Cek apakah tanggal keberangkatan dengan tujuan sama sudah di-booked/lunas oleh client lain
        $isBooked = Booking::where('tujuan_wisata', $tujuanWisata)
            ->where('tanggal_keberangkatan', $request->tanggal_keberangkatan)
            ->whereIn('status', ['booked', 'lunas'])
            ->exists();

        if ($isBooked) {
            return back()->withInput()->withErrors([
                'tanggal_keberangkatan' => 'Maaf, tanggal keberangkatan untuk paket ini sudah dipesan oleh rombongan lain. Silakan pilih tanggal lain.'
            ]);
        }

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
            'tujuan_wisata' => $tujuanWisata,
            'biaya_sewa' => $totalSewa,
            'sisa_pembayaran' => $totalSewa,
            'status' => 'pending',
        ]);

        return redirect()->route('client.riwayat')->with('success', 'Permintaan booking berhasil diajukan! Silakan Lakukan Pembayaran.');
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

        $today = Carbon::today();
        $tglBerangkat = Carbon::parse($booking->tanggal_keberangkatan);
        $selisihHari = $today::diffInDays($tglBerangkat, false);

        // Aturan Nominal Minimal: DP minimal 2jt, atau jika H-7 wajib Pelunasan
        $minBayar = 2000000;
        if ($selisihHari <= 7 || $booking->status === 'booked') {
            $minBayar = $booking->sisa_pembayaran;
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,tunai',
            'jumlah_bayar' => 'required|numeric|min:' . min($minBayar, $booking->sisa_pembayaran) . '|max:' . $booking->sisa_pembayaran,
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->metode_pembayaran === 'transfer' && $request->hasFile('bukti_pembayaran')) {
            if (class_exists('CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('bukti_pembayaran')->getRealPath(), [
                    'folder' => 'bukti_pembayaran'
                ])->getSecurePath();
                $booking->bukti_pembayaran = $uploadedFileUrl;
            } else {
                $booking->bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }
        }

        $booking->metode_pembayaran = $request->metode_pembayaran;
        $booking->pembayaran_terakhir = $request->jumlah_bayar; // Simpan draft ke field penampung temporary
        $booking->status = 'pending_verification';
        $booking->save();

        return redirect()->route('client.riwayat')->with('success', 'Konfirmasi pembayaran dikirim! Menunggu verifikasi admin.');
    }
}
