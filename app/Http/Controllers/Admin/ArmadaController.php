<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Armada;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArmadaController extends Controller
{
    public function index(Request $request)
    {
        $query = Armada::query();

        if ($request->filled('search')) {
            $query->where('jenis_armada', 'like', "%{$request->search}%");
        }

        $armadaList = $query->latest()->paginate(10);

        return view('admin.armada.index', compact('armadaList'));
    }

    public function create()
    {
        return view('admin.armada.create');
    }

    /**
     * Menyimpan data armada baru dengan validasi kombinasi unik
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_armada'   => 'required|string|max:100',
            'kapasitas'      => [
                'required',
                'integer',
                'min:1',
                // Pengecekan agar jenis_armada + kapasitas tidak duplikat
                Rule::unique('armada')->where(function ($query) use ($request) {
                    return $query->where('jenis_armada', $request->jenis_armada)
                        ->where('kapasitas', $request->kapasitas);
                }),
            ],
            'sewa_inc_solar' => 'required|numeric|min:0',
        ], [
            // Custom message jika terdapat duplikasi
            'kapasitas.unique' => 'Kombinasi Jenis Armada "' . $request->jenis_armada . '" dengan kapasitas ' . $request->kapasitas . ' seat sudah ada di database.'
        ]);

        Armada::create($validated);

        return redirect()->route('admin.armada.index')
            ->with('success', 'Data armada kendaraan berhasil ditambahkan!');
    }

    public function edit(Armada $armada)
    {
        return view('admin.armada.edit', compact('armada'));
    }

    /**
     * Memperbarui data armada (mengabaikan ID dirinya sendiri saat validasi)
     */
    public function update(Request $request, Armada $armada)
    {
        $validated = $request->validate([
            'jenis_armada'   => 'required|string|max:100',
            'kapasitas'      => [
                'required',
                'integer',
                'min:1',
                // Pengecekan unik kombinasi & abaikan ID armada saat ini
                Rule::unique('armada')->where(function ($query) use ($request) {
                    return $query->where('jenis_armada', $request->jenis_armada)
                        ->where('kapasitas', $request->kapasitas);
                })->ignore($armada->id),
            ],
            'sewa_inc_solar' => 'required|numeric|min:0',
        ], [
            'kapasitas.unique' => 'Kombinasi Jenis Armada "' . $request->jenis_armada . '" dengan kapasitas ' . $request->kapasitas . ' seat sudah ada di database.'
        ]);

        $armada->update($validated);

        return redirect()->route('admin.armada.index')
            ->with('success', 'Data armada berhasil diperbarui!');
    }

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
