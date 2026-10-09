<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use App\Models\Destinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaketWisataController extends Controller
{
    public function index(Request $request)
    {
        $query = PaketWisata::with('destinasi');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_paket', 'like', "%{$request->search}%")
                    ->orWhere('nama_sub_paket', 'like', "%{$request->search}%");
            });
        }

        $paketList = $query->latest()->paginate(10);

        return view('admin.paket-wisata.index', compact('paketList'));
    }

    public function create()
    {
        $destinasiList = Destinasi::orderBy('nama_destinasi')->get();
        return view('admin.paket-wisata.create', compact('destinasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket'       => 'required|string|max:150',
            'nama_sub_paket'   => 'nullable|string|max:100',
            'durasi'           => 'required|string|max:50',
            'harga_mulai_from' => 'required|numeric|min:0',
            'fasilitas'        => 'required|string',
            'is_active'        => 'required|boolean',
            'destinasi_ids'    => 'required|array|min:1',
            'destinasi_ids.*'  => 'exists:destinasi,id',
        ]);

        DB::transaction(function () use ($validated) {
            // Format Slug: Gabungkan nama_paket dan nama_sub_paket jika ada
            $slugBase = $validated['nama_paket'];
            if (!empty($validated['nama_sub_paket'])) {
                $slugBase .= ' ' . $validated['nama_sub_paket'];
            }
            $validated['slug'] = Str::slug($slugBase) . '-' . Str::random(5);

            $paket = PaketWisata::create($validated);

            $syncData = [];
            foreach ($validated['destinasi_ids'] as $destinasiId) {
                $syncData[$destinasiId] = [
                    'uuid' => (string) Str::uuid()
                ];
            }

            $paket->destinasi()->sync($syncData);
        });

        return redirect()->route('admin.paket-wisata.index')
            ->with('success', 'Paket wisata berhasil diterbitkan!');
    }

    public function edit(PaketWisata $paketWisata)
    {
        $paketWisata->load('destinasi');
        $destinasiList     = Destinasi::orderBy('nama_destinasi')->get();
        $selectedDestinasi = $paketWisata->destinasi->pluck('id')->toArray();

        return view('admin.paket-wisata.edit', compact('paketWisata', 'destinasiList', 'selectedDestinasi'));
    }

    public function update(Request $request, PaketWisata $paketWisata)
    {
        $validated = $request->validate([
            'nama_paket'       => 'required|string|max:150',
            'nama_sub_paket'   => 'nullable|string|max:100',
            'durasi'           => 'required|string|max:50',
            'harga_mulai_from' => 'required|numeric|min:0',
            'fasilitas'        => 'required|string',
            'is_active'        => 'required|boolean',
            'destinasi_ids'    => 'required|array|min:1',
            'destinasi_ids.*'  => 'exists:destinasi,id',
        ]);

        DB::transaction(function () use ($validated, $paketWisata) {
            if ($paketWisata->nama_paket !== $validated['nama_paket'] || $paketWisata->nama_sub_paket !== $validated['nama_sub_paket']) {
                $slugBase = $validated['nama_paket'];
                if (!empty($validated['nama_sub_paket'])) {
                    $slugBase .= ' ' . $validated['nama_sub_paket'];
                }
                $validated['slug'] = Str::slug($slugBase) . '-' . Str::random(5);
            }

            $paketWisata->update($validated);

            $syncData = [];
            foreach ($validated['destinasi_ids'] as $destinasiId) {
                $syncData[$destinasiId] = [
                    'uuid' => (string) Str::uuid()
                ];
            }

            $paketWisata->destinasi()->sync($syncData);
        });

        return redirect()->route('admin.paket-wisata.index')
            ->with('success', 'Paket wisata berhasil diperbarui!');
    }

    public function destroy(PaketWisata $paketWisata)
    {
        DB::transaction(function () use ($paketWisata) {
            $paketWisata->destinasi()->detach();
            $paketWisata->delete();
        });

        return redirect()->route('admin.paket-wisata.index')
            ->with('success', 'Paket wisata berhasil dihapus!');
    }
}
