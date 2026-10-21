<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class GaleriController extends Controller
{
    public function index()
    {
        $galeriList = Galeri::with('paketWisata')->latest()->paginate(12);
        return view('admin.galeri.index', compact('galeriList'));
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:150',
            'file_gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('file_gambar')) {
            // Jika menggunakan CloudinaryLaravel Package
            if (class_exists('CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('file_gambar')->getRealPath(), [
                    'folder' => 'galeri'
                ])->getSecurePath();
                $validated['file_gambar'] = $uploadedFileUrl;
            } else {
                // Fallback storage disk 'public'
                $validated['file_gambar'] = $request->file('file_gambar')->store('galeri', 'public');
            }
        }

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto dokumentasi berhasil diunggah!');
    }

    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:150',
            'file_gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('file_gambar')) {
            // Hapus gambar lama jika merupakan file lokal
            if ($galeri->file_gambar && !\Illuminate\Support\Str::startsWith($galeri->file_gambar, ['http://', 'https://'])) {
                if (Storage::disk('public')->exists($galeri->file_gambar)) {
                    Storage::disk('public')->delete($galeri->file_gambar);
                }
            }

            if (class_exists('CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('file_gambar')->getRealPath(), [
                    'folder' => 'galeri'
                ])->getSecurePath();
                $validated['file_gambar'] = $uploadedFileUrl;
            } else {
                $validated['file_gambar'] = $request->file('file_gambar')->store('galeri', 'public');
            }
        }

        Galeri::update($validated);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Informasi galeri berhasil diperbarui!');
    }

    public function destroy(Galeri $galeri)
    {
        if ($galeri->file_gambar && !\Illuminate\Support\Str::startsWith($galeri->file_gambar, ['http://', 'https://'])) {
            if (Storage::disk('public')->exists($galeri->file_gambar)) {
                Storage::disk('public')->delete($galeri->file_gambar);
            }
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Foto galeri berhasil dihapus!');
    }
}
