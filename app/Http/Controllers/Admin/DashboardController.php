<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Destinasi;
use App\Models\PaketWisata;
use App\Models\Armada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan ringkasan statistik data pada dashboard admin.
     */
    public function index()
    {
        $totalProposal = Proposal::count();
        $proposalApproved = Proposal::where('status', 'approved')->count();
        $proposalDraft = Proposal::where('status', 'draft')->count();
        $proposalPending = Proposal::where('status', 'sent')->count();

        $totalDestinasi = Destinasi::count();
        $totalPaketWisata = PaketWisata::where('is_active', true)->count();
        $totalArmada = Armada::count();

        $recentProposals = Proposal::with('user')
            ->latest()
            ->take(5)
            ->get();

        $totalEstimasiOmset = Proposal::where('status', 'approved')
            ->select(DB::raw('SUM(harga_akhir_per_pax * jumlah_peserta) as total_omset'))
            ->value('total_omset') ?? 0;

        return view('admin.dashboard', compact(
            'totalProposal',
            'proposalApproved',
            'proposalDraft',
            'proposalPending',
            'totalDestinasi',
            'totalPaketWisata',
            'totalArmada',
            'recentProposals',
            'totalEstimasiOmset'
        ));
    }
}
