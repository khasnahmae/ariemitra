<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik ringkas untuk Client
        $totalPemesanan = Booking::where('user_id', $userId)->count();
        $bookingAktif = Booking::where('user_id', $userId)
            ->whereIn('status', ['pending', 'sent', 'booked'])
            ->count();
        $totalSelesai = Booking::where('user_id', $userId)
            ->where('status', 'lunas')
            ->count();

        // Ambil 3 transaksi terbaru
        $recentBookings = Booking::with('proposal.PaketWisata')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        // Ambil proposal paket wisata pilihan untuk promo/rekomendasi
        $paketRekomendasi = Proposal::with('PaketWisata')
            ->where('status', 'publish')
            ->latest()
            ->take(3)
            ->get();

        return view('client.dashboard', compact(
            'totalPemesanan',
            'bookingAktif',
            'totalSelesai',
            'recentBookings',
            'paketRekomendasi'
        ));
    }
}
