<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class PaketWisataClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::where('status', 'publish')
            ->with(['PaketWisata', 'proposalDestinasi.destinasi']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_sub_paket', 'like', "%{$request->search}%")
                    ->orWhereHas('PaketWisata', function ($p) use ($request) {
                        $p->where('nama_paket', 'like', "%{$request->search}%");
                    });
            });
        }

        $proposalList = $query->latest()->paginate(9);

        return view('client.paket.index', compact('proposalList'));
    }

    public function show($uuid)
    {
        $paket = Proposal::where('status', 'publish')
            ->where('uuid', $uuid)
            ->with([
                'PaketWisata',
                'proposalDestinasi.destinasi',
                'proposalArmada.armada',
                'proposalBiayaKomponen'
            ])
            ->firstOrFail();

        return view('client.paket.show', compact('paket'));
    }
}
