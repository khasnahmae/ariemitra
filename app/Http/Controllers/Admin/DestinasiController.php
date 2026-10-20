<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'lokasi'         => [
                'required',
                'string',
                'max:100',
                // Pengecekan agar nama_destinasi + lokasi tidak duplikat
                Rule::unique('destinasi')->where(function ($query) use ($request) {
                    return $query->where('nama_destinasi', $request->nama_destinasi)
                        ->where('lokasi', $request->lokasi);
                }),
            ],
            'htm_per_orang'  => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'lokasi.unique' => 'Destinasi "' . $request->nama_destinasi . '" di lokasi "' . $request->lokasi . '" sudah ada di database.'
        ]);

        if ($request->hasFile('foto')) {
            try {
                $uploaded = $request->file('foto')->storeOnCloudinary('destinasi');
                $validated['foto'] = $uploaded->getSecurePath();
            } catch (\Exception $e) {
                return back()
                    ->withInput()
                    ->with('error', 'Gagal mengunggah foto ke Cloudinary: ' . $e->getMessage());
            }
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
            'lokasi'         => [
                'required',
                'string',
                'max:100',
                // Pengecekan unik kombinasi & abaikan ID destinasi saat ini
                Rule::unique('destinasi')->where(function ($query) use ($request) {
                    return $query->where('nama_destinasi', $request->nama_destinasi)
                        ->where('lokasi', $request->lokasi);
                })->ignore($destinasi->id),
            ],
            'htm_per_orang'  => 'required|numeric|min:0',
            'keterangan'     => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'lokasi.unique' => 'Destinasi "' . $request->nama_destinasi . '" di lokasi "' . $request->lokasi . '" sudah ada di database.'
        ]);

        if ($request->hasFile('foto')) {
            try {
                $uploaded = $request->file('foto')->storeOnCloudinary('destinasi');
                $validated['foto'] = $uploaded->getSecurePath();
            } catch (\Exception $e) {
                return back()
                    ->withInput()
                    ->with('error', 'Gagal mengunggah foto ke Cloudinary: ' . $e->getMessage());
            }
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
