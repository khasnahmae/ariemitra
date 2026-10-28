<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Destinasi;
use App\Models\PaketWisata;
use App\Models\Armada;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan ringkasan statistik data pada dashboard admin.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            $totalPemesanan = Booking::where('user_id', $user->id)->count();
            $bookingAktif = Booking::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'sent', 'booked'])
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



        $totalProposal = Proposal::count();
        $proposalApproved = Proposal::where('status', 'approved')->count();
        $proposalDraft = Proposal::where('status', 'draft')->count();
        $proposalPending = Proposal::where('status', 'sent')->count();

        $totalDestinasi = Destinasi::count();
        $totalPaketWisata = PaketWisata::count();
        $totalArmada = Armada::count();

        $recentProposals = Proposal::with(['user', 'PaketWisata'])
            ->latest()
            ->take(5)
            ->get();

        $totalEstimasiOmset = Proposal::where('status', 'approved')
            ->select(DB::raw('SUM(harga_akhir_per_pax * jumlah_peserta) as total_omset'))
            ->value('total_omset') ?? 0;



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


        return view('admin.dashboard', compact(
            'totalProposal',
            'proposalApproved',
            'proposalDraft',
            'proposalPending',
            'totalDestinasi',
            'totalPaketWisata',
            'totalArmada',
            'recentProposals',
            'totalEstimasiOmset',
            'totalPemesanan',
            'bookingAktif',
            'totalSelesai',
            'recentBookings',
            'paketRekomendasi'
        ));
    }
}
