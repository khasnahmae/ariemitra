<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookingController extends Controller
{
    // Menampilkan Form Booking dengan Tanggal Terkunci (Disabled Dates)
    public function create($proposalUuid)
    {
        $paket = Proposal::with(['PaketWisata', 'proposalDestinasi.destinasi'])
            ->where('uuid', $proposalUuid)
            ->firstOrFail();

        // Cari tanggal yang sudah ter-booking & terverifikasi (booked/lunas) untuk tujuan wisata ini
        $bookedDates = Booking::where('proposal_id', $paket->id)
            ->whereIn('status', ['booked', 'lunas', 'pending_verification'])
            ->pluck('tanggal_keberangkatan')
            ->toArray();

        return view('client.booking_create', compact('paket', 'bookedDates'));
    }

    public function store(Request $request, $proposalUuid)
    {
        $proposal = Proposal::where('uuid', $proposalUuid)->firstOrFail();

        // Validasi H-7 Keberangkatan Minimum
        $minDate = Carbon::now()->addDays(7)->format('Y-m-d');

        $request->validate([
            'alamat_penjemputan' => 'required|string',
            'telepon_pemesan' => 'required|string|max:20',
            'tanggal_keberangkatan' => [
                'required',
                'date',
                'after_or_equal:' . $minDate,
            ],
        ], [
            'tanggal_keberangkatan.after_or_equal' => 'Pemesanan harus dilakukan minimal H-7 sebelum tanggal keberangkatan.',
        ]);

        // CEK MENCEGAH BENTROK TANGGAL DI SERVER SIDE
        $isConflict = Booking::where('proposal_id', $proposal->id)
            ->where('tanggal_keberangkatan', $request->tanggal_keberangkatan)
            ->whereIn('status', ['booked', 'lunas', 'pending_verification'])
            ->exists();

        if ($isConflict) {
            return back()->withInput()->withErrors(['tanggal_keberangkatan' => 'Tanggal keberangkatan untuk paket ini sudah dipesan oleh client lain. Silakan pilih tanggal lain.']);
        }

        $totalSewa = $proposal->harga_akhir_per_pax * $proposal->jumlah_peserta;
        $noInvoice = 'INV/' . date('Ymd') . '/' . rand(100, 999);

        $namaPaket = $proposal->PaketWisata->nama_paket ?? 'Paket Wisata';
        $subPaket = $proposal->nama_sub_paket ?? '';

        Booking::create([
            'no_invoice' => $noInvoice,
            'user_id' => Auth::id(),
            'proposal_id' => $proposal->id,
            'nama_pemesan' => Auth::user()->nama,
            'telepon_pemesan' => $request->telepon_pemesan,
            'alamat_penjemputan' => $request->alamat_penjemputan,
            'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
            'tujuan_wisata' => $namaPaket . ($subPaket ? ' - ' . $subPaket : ''),
            'biaya_sewa' => $totalSewa,
            'sisa_pembayaran' => $totalSewa,
            'status' => 'pending',
            'is_read' => false, // Set false agar memicu badge di akun admin
        ]);

        return redirect()->route('client.riwayat')->with('success', 'Permintaan booking berhasil dikirim! Silakan melakukan konfirmasi DP.');
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

        // Tentukan batas minimal pembayaran (DP1 minimal 2jt, atau jika sisa < 2jt maka seharga sisa)
        $minBayar = ($booking->dp_1 == 0) ? min(2000000, $booking->sisa_pembayaran) : 100000;

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,tunai',
            'jumlah_bayar' => 'required|numeric|min:' . $minBayar . '|max:' . $booking->sisa_pembayaran,
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'jumlah_bayar.min' => 'Pembayaran Uang Muka (DP 1) minimal Rp ' . number_format($minBayar, 0, ',', '.'),
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
            $booking->metode_pembayaran = 'transfer';
        } else {
            $booking->metode_pembayaran = 'tunai';
        }

        // Simpan sementara di nominal_konfirmasi agar disetujui oleh admin
        $booking->nominal_konfirmasi = $request->jumlah_bayar;
        $booking->status = 'pending_verification';
        $booking->is_read = false; // Memicu badge notifikasi di admin
        $booking->save();

        return redirect()->route('client.riwayat')->with('success', 'Bukti konfirmasi pembayaran berhasil dikirim! Menunggu verifikasi admin.');
    }
}
