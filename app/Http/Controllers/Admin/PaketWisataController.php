<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use Illuminate\Http\Request;

class PaketWisataController extends Controller
{
    public function index(Request $request)
    {
        $query = PaketWisata::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_paket', 'like', "%{$request->search}%")
                    ->orWhere('nomor_paket', 'like', "%{$request->search}%");
            });
        }

        $paketList = $query->orderBy('nomor_paket', 'asc')->paginate(10);

        return view('admin.paket-wisata.index', compact('paketList'));
    }

    public function create()
    {
        // Generasi nomor paket otomatis berurutan dari 1
        $lastPaket = PaketWisata::orderBy('id', 'desc')->first();
        $nextNomorPaket = $lastPaket ? ((int) $lastPaket->nomor_paket + 1) : 1;

        return view('admin.paket-wisata.create', compact('nextNomorPaket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:150',
        ]);

        // Auto generate nomor paket secara otomatis di server
        $lastPaket = PaketWisata::orderBy('id', 'desc')->first();
        $validated['nomor_paket'] = $lastPaket ? ((int) $lastPaket->nomor_paket + 1) : 1;

        PaketWisata::create($validated);

        return redirect()->route('admin.paket-wisata.index')
            ->with('success', 'Kategori paket wisata berhasil ditambahkan!');
    }

    public function edit(PaketWisata $paketWisata)
    {
        return view('admin.paket-wisata.edit', compact('paketWisata'));
    }

    public function update(Request $request, PaketWisata $paketWisata)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:150',
        ]);

        $paketWisata->update([
            'nama_paket' => $validated['nama_paket']
        ]);

        return redirect()->route('admin.paket-wisata.index')
            ->with('success', 'Kategori paket wisata berhasil diperbarui!');
    }

    public function destroy(PaketWisata $paketWisata)
    {
        $paketWisata->delete();

        return redirect()->route('admin.paket-wisata.index')
            ->with('success', 'Kategori paket wisata berhasil dihapus!');
    }
}
