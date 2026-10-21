<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class PaketWisataController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::with(['PaketWisata', 'proposalDestinasi.destinasi']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_sub_paket', 'like', "%{$request->search}%")
                    ->orWhereHas('PaketWisata', function ($p) use ($request) {
                        $p->where('nama_paket', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->filled('max_price')) {
            $query->where('harga_akhir_per_pax', '<=', $request->max_price);
        }

        $paketList = $query->latest()->paginate(9);

        return view('frontend.paket.index', compact('paketList'));
    }

    public function show($uuid)
    {
        $paket = Proposal::with([
            'PaketWisata',
            'proposalDestinasi.destinasi',
            'proposalArmada.armada',
            'proposalBiayaKomponen'
        ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $namaLengkapPaket = "Paket " . ($paket->PaketWisata->nama_paket ?? '') . " (" . $paket->nama_sub_paket . ")";
        $waMessage = urlencode("Halo admin Biro Perjalanan, saya tertarik dengan paket wisata: *" . $namaLengkapPaket . "*. Bisakah kirimkan informasi lebih lanjut?");
        $waNumber = "6287722082751";
        $waLink = "https://wa.me/{$waNumber}?text={$waMessage}";

        return view('frontend.paket.show', compact('paket', 'waLink'));
    }
}
