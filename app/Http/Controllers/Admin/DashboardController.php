<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Destinasi;
use App\Models\Armada;
use App\Models\PaketWisata;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // TAMPILAN ROLE CLIENT
        if ($user && $user->role === 'client') {
            $totalPemesanan = Booking::where('user_id', $user->id)->count();

            // Disesuaikan dengan status enum tanpa 'sent'
            $bookingAktif = Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'pending_verification', 'booked'])
                ->count();

            $totalSelesai = Booking::where('user_id', $user->id)
                ->where('status', 'lunas')
                ->count();

            $recentBookings = Booking::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            $paketRekomendasi = Proposal::with('PaketWisata')
                ->where('status', 'publish')
                ->latest()
                ->take(3)
                ->get();

            return view('admin.dashboard', compact(
                'totalPemesanan',
                'bookingAktif',
                'totalSelesai',
                'recentBookings',
                'paketRekomendasi'
            ));
        }

        // TAMPILAN ROLE ADMIN ATAU OWNER
        $totalProposal = Proposal::count();
        $totalDestinasi = Destinasi::count();
        $totalArmada = Armada::count();
        $totalPaketWisata = PaketWisata::count();

        // Total Estimasi Omset dari transaksi approved / booked / lunas
        $totalEstimasiOmset = Proposal::whereIn('status', ['approved', 'publish'])->get()->sum(function ($p) {
            return $p->harga_akhir_per_pax * $p->jumlah_peserta;
        });

        $recentProposals = Proposal::with('PaketWisata')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProposal',
            'totalDestinasi',
            'totalArmada',
            'totalPaketWisata',
            'totalEstimasiOmset',
            'recentProposals'
        ));
    }
}
