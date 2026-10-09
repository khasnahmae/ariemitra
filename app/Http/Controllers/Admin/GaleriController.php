<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    /**
     * Menampilkan galeri dokumentasi perjalanan.
     */
    public function index()
    {
        $galeriList = Galeri::with('paketWisata')->latest()->paginate(12);
        return view('admin.galeri.index', compact('galeriList'));
    }

    /**
     * Form upload foto galeri.
     */
    public function create()
    {
        $paketList = PaketWisata::where('is_active', true)->get();
        return view('admin.galeri.create', compact('paketList'));
    }

    /**
     * Menyimpan foto galeri baru ke storage server.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:150',
            'paket_id'    => 'nullable|exists:paket_wisata,id',
            'file_gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('file_gambar')) {
            $path = $request->file('file_gambar')->store('galeri', 'public');
            $validated['file_gambar'] = $path;
        }

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto dokumentasi berhasil diunggah!');
    }

    /**
     * Form edit foto galeri.
     */
    public function edit(Galeri $galeri)
    {
        $paketList = PaketWisata::where('is_active', true)->get();
        return view('admin.galeri.edit', compact('galeri', 'paketList'));
    }

    /**
     * Memperbarui informasi foto / mengganti gambar.
     */
    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:150',
            'paket_id'    => 'nullable|exists:paket_wisata,id',
            'file_gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('file_gambar')) {
            if ($galeri->file_gambar && Storage::disk('public')->exists($galeri->file_gambar)) {
                Storage::disk('public')->delete($galeri->file_gambar);
            }
            $validated['file_gambar'] = $request->file('file_gambar')->store('galeri', 'public');
        }

        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Informasi galeri berhasil diperbarui!');
    }

    /**
     * Menghapus foto dari galeri dan storage.
     */
    public function destroy(Galeri $galeri)
    {
        if ($galeri->file_gambar && Storage::disk('public')->exists($galeri->file_gambar)) {
            Storage::disk('public')->delete($galeri->file_gambar);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus!');
    }
}
