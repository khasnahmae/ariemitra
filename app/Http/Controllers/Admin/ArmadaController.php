<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    /**
     * Menampilkan daftar master data armada kendaraan.
     */
    public function index(Request $request)
    {
        $query = Armada::query();

        if ($request->filled('search')) {
            $query->where('jenis_armada', 'like', "%{$request->search}%");
        }

        $armadaList = $query->latest()->paginate(10);

        return view('admin.armada.index', compact('armadaList'));
    }

    /**
     * Form tambah armada baru.
     */
    public function create()
    {
        return view('admin.armada.create');
    }

    /**
     * Menyimpan data armada baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_armada'   => 'required|string|max:100',
            'kapasitas'      => 'required|integer|min:1',
            'sewa_inc_solar' => 'required|numeric|min:0',
        ]);

        Armada::create($validated);

        return redirect()->route('admin.armada.index')
            ->with('success', 'Data armada kendaraan berhasil ditambahkan!');
    }

    /**
     * Form edit data armada.
     */
    public function edit(Armada $armada)
    {
        return view('admin.armada.edit', compact('armada'));
    }

    /**
     * Memperbarui data armada.
     */
    public function update(Request $request, Armada $armada)
    {
        $validated = $request->validate([
            'jenis_armada'   => 'required|string|max:100',
            'kapasitas'      => 'required|integer|min:1',
            'sewa_inc_solar' => 'required|numeric|min:0',
        ]);

        $armada->update($validated);

        return redirect()->route('admin.armada.index')
            ->with('success', 'Data armada berhasil diperbarui!');
    }

    /**
     * Menghapus armada dari database.
     */
    public function destroy(Armada $armada)
    {
        try {
            $armada->delete();
            return redirect()->route('admin.armada.index')
                ->with('success', 'Data armada berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.armada.index')
                ->with('error', 'Gagal menghapus! Armada digunakan dalam proposal transaksi.');
        }
    }
}
