<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use Illuminate\Http\Request;

class PaketWisataController extends Controller
{
    /**
     * Menampilkan Katalog Paket Wisata Publik.
     */
    public function index(Request $request)
    {
        $query = PaketWisata::with('destinasi')->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('nama_paket', 'like', "%{$request->search}%");
        }

        if ($request->filled('max_price')) {
            $query->where('harga_mulai_from', '<=', $request->max_price);
        }

        $paketList = $query->latest()->paginate(9);

        return view('frontend.paket.index', compact('paketList'));
    }

    /**
     * Menampilkan Detail Paket Wisata beserta link order via WhatsApp.
     */
    public function show($slug)
    {
        $paket = PaketWisata::with(['destinasi', 'galeri'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $waMessage = urlencode("Halo admin Biro Perjalanan, saya tertarik dengan paket wisata: *" . $paket->nama_paket . "*. Bisakah kirimkan penawaran/proposal resminya?");
        $waNumber = "6281234567890"; // Ganti dengan nomor WhatsApp kantor biro
        $waLink = "https://wa.me/{$waNumber}?text={$waMessage}";

        return view('frontend.paket.show', compact('paket', 'waLink'));
    }
}
