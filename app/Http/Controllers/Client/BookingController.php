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

        // 1. CEK BENTROK TANGGAL DENGAN BOOKING LAIN YANG SUDAH BOOKED / LUNAS
        $isBooked = Booking::where('tujuan_wisata', $tujuanWisata)
            ->where('tanggal_keberangkatan', $request->tanggal_keberangkatan)
            ->whereIn('status', ['booked', 'lunas', 'pending_verification'])
            ->exists();

        if ($isBooked) {
            return back()->withInput()->with('error', 'Tanggal keberangkatan untuk paket tujuan ini sudah dipesan oleh client lain. Silakan pilih tanggal lain!');
        }

        $totalSewa = $proposal->harga_akhir_per_pax * $proposal->jumlah_peserta;
        $noInvoice = 'INV/' . date('Ymd') . '/' . rand(1000, 9999);

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
            'is_read_admin' => false,
        ]);

        return redirect()->route('client.riwayat')->with('success', 'Permintaan booking berhasil diajukan! Silakan tunggu invoice dari Admin.');
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

        // 2. VALIDASI BATAS WAKTU PELUNASAN H-7 SEBELUM KEBERANGKATAN
        $tglKeberangkatan = Carbon::parse($booking->tanggal_keberangkatan);
        $hariSisa = Carbon::now()->startOfDay()->diffInDays($tglKeberangkatan, false);

        if ($hariSisa < 7 && $booking->status !== 'lunas') {
            return back()->with('error', 'Pembayaran gagal. Pelunasan paket wajib dilakukan maksimal H-7 sebelum tanggal keberangkatan!');
        }

        // 3. VALIDASI NOMINAL DP (DP 1 MINIMAL 2 JUTA ATAU SEJUMLAH SISA TAGIHAN JIKA KURANG DARI 2 JUTA)
        $minBayar = ($booking->dp_1 == 0) ? min(2000000, $booking->sisa_pembayaran) : 100000;

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,tunai',
            'jumlah_bayar' => "required|numeric|min:{$minBayar}|max:{$booking->sisa_pembayaran}",
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
        $booking->nominal_pending = $request->jumlah_bayar; // Disimpan sementara hingga diverifikasi Admin
        $booking->status = 'pending_verification';
        $booking->is_read_admin = false; // Memicu badge merah pada akun admin
        $booking->save();

        return redirect()->route('client.riwayat')->with('success', 'Konfirmasi pembayaran berhasil dikirim! Menunggu verifikasi Admin.');
    }
}
