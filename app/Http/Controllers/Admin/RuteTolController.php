<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RuteTol;
use Illuminate\Http\Request;

class RuteTolController extends Controller
{

    public function index(Request $request)
    {
        $query = RuteTol::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_rute', 'like', "%{$search}%")
                ->orWhere('golongan', 'like', "%{$search}%");
        }

        $ruteTolList = $query->latest()->paginate(10);

        return view('admin.rute-tol.index', compact('ruteTolList'));
    }

    /**
     * Form tambah rute tol baru.
     */
    public function create()
    {
        return view('admin.rute-tol.create');
    }

    /**
     * Menyimpan data rute tol baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rute'   => 'required|string|max:150',
            'golongan'    => 'required|string|max:20',
            'tarif_total' => 'required|numeric|min:0',
        ]);

        RuteTol::create($validated);

        return redirect()->route('admin.rute-tol.index')
            ->with('success', 'Data rute tol berhasil ditambahkan!');
    }

    /**
     * Form edit data rute tol.
     */
    public function edit(RuteTol $ruteTol)
    {
        return view('admin.rute-tol.edit', compact('ruteTol'));
    }

    /**
     * Memperbarui data rute tol.
     */
    public function update(Request $request, RuteTol $ruteTol)
    {
        $validated = $request->validate([
            'nama_rute'   => 'required|string|max:150',
            'golongan'    => 'required|string|max:20',
            'tarif_total' => 'required|numeric|min:0',
        ]);

        $ruteTol->update($validated);

        return redirect()->route('admin.rute-tol.index')
            ->with('success', 'Data rute tol berhasil diperbarui!');
    }

    /**
     * Menghapus rute tol dari database.
     */
    public function destroy(RuteTol $ruteTol)
    {
        try {
            $ruteTol->delete();
            return redirect()->route('admin.rute-tol.index')
                ->with('success', 'Data rute tol berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.rute-tol.index')
                ->with('error', 'Gagal menghapus! Rute tol masih digunakan pada proposal.');
        }
    }
}
