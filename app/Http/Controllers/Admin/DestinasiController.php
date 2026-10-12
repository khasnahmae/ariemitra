<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DestinasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Destinasi::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_destinasi', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%");
        }

        $destinasiList = $query->latest()->paginate(10);

        return view('admin.destinasi.index', compact('destinasiList'));
    }

    public function create()
    {
        return view('admin.destinasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_destinasi' => 'required|string|max:150',
            'lokasi'         => 'required|string|max:100',
            'htm_per_orang'  => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Upload langsung ke Cloudinary (folder 'destinasi') dan ambil URL publiknya
            $uploadedFileUrl = $request->file('foto')->storeOnCloudinary('destinasi')->getSecurePath();
            $validated['foto'] = $uploadedFileUrl;
        }

        Destinasi::create($validated);

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Data destinasi wisata berhasil ditambahkan!');
    }

    public function edit(Destinasi $destinasi)
    {
        return view('admin.destinasi.edit', compact('destinasi'));
    }

    public function update(Request $request, Destinasi $destinasi)
    {
        $validated = $request->validate([
            'nama_destinasi' => 'required|string|max:150',
            'lokasi'         => 'required|string|max:100',
            'htm_per_orang'  => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Upload foto baru ke Cloudinary
            $uploadedFileUrl = $request->file('foto')->storeOnCloudinary('destinasi')->getSecurePath();
            $validated['foto'] = $uploadedFileUrl;
        }

        $destinasi->update($validated);

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Data destinasi wisata berhasil diperbarui!');
    }

    public function destroy(Destinasi $destinasi)
    {
        try {
            $destinasi->delete();

            return redirect()->route('admin.destinasi.index')
                ->with('success', 'Destinasi wisata berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.destinasi.index')
                ->with('error', 'Gagal menghapus! Destinasi terikat dengan proposal atau paket wisata.');
        }
    }
}
