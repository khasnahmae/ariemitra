<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Galeri;

class HomeController extends Controller
{
    public function index()
    {
        $paketPopuler = Proposal::with(['PaketWisata', 'proposalDestinasi.destinasi'])
            ->latest()
            ->take(6)
            ->get();

        $galeriTerbaru = Galeri::latest()->take(6)->get();

        return view('frontend.home', compact('paketPopuler', 'galeriTerbaru'));
    }

    public function profil()
    {
        return view('frontend.profil');
    }

    public function galeri()
    {
        $galeriList = Galeri::latest()->paginate(12);
        return view('frontend.galeri', compact('galeriList'));
    }

    public function kontak()
    {
        return view('frontend.kontak');
    }
}
