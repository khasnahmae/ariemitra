<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use App\Models\Destinasi;
use App\Models\Galeri;

class HomeController extends Controller
{
    /**
     * Menampilkan Halaman Beranda (Landing Page).
     */
    public function index()
    {
        $paketPopuler = PaketWisata::with('destinasi')
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $destinasiPilihan = Destinasi::latest()->take(8)->get();

        $galeriTerbaru = Galeri::latest()->take(6)->get();

        return view('frontend.home', compact('paketPopuler', 'destinasiPilihan', 'galeriTerbaru'));
    }

    /**
     * Menampilkan Halaman Profil Biro Perjalanan.
     */
    public function profil()
    {
        return view('frontend.profil');
    }

    /**
     * Menampilkan Halaman Galeri & Dokumentasi Perjalanan.
     */
    public function galeri()
    {
        $galeriList = Galeri::with('paketWisata')->latest()->paginate(12);
        return view('frontend.galeri', compact('galeriList'));
    }

    /**
     * Menampilkan Halaman Kontak & Informasi Pemesanan.
     */
    public function kontak()
    {
        return view('frontend.kontak');
    }
}
