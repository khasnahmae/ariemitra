<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Destinasi;
use App\Models\RuteTol;
use App\Models\Armada;
use App\Models\ProposalDestinasi;
use App\Models\ProposalTol;
use App\Models\ProposalArmada;
use App\Models\ProposalBiayaKomponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::with('user');

        if ($request->filled('search')) {
            $query->where('kode_proposal', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $proposalList = $query->latest()->paginate(10);

        return view('admin.proposal.index', compact('proposalList'));
    }

    public function create()
    {
        $destinasiList = Destinasi::orderBy('nama_destinasi')->get();
        $ruteTolList   = RuteTol::orderBy('nama_rute')->get();
        $armadaList    = Armada::orderBy('jenis_armada')->get();

        return view('admin.proposal.create', compact('destinasiList', 'ruteTolList', 'armadaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_menginap'         => 'required|in:ya,tidak',
            'jumlah_hari'         => 'required|integer|min:1',
            'jumlah_malam'        => 'required|integer|min:0',
            'jumlah_peserta'      => 'required|integer|min:1',
            'armada_id'          => 'required|exists:armada,id',
            'jumlah_unit_armada'  => 'required|integer|min:1',
            'destinasi_ids'      => 'required|array|min:1',
            'destinasi_ids.*'    => 'exists:destinasi,id',
            'rute_tol_ids'       => 'nullable|array',
            'rute_tol_ids.*'     => 'exists:rute_tol,id',

            // Fixed Standard Costs
            'biaya_sewa_tambahan' => 'nullable|numeric|min:0',
            'biaya_bbm'           => 'nullable|numeric|min:0',
            'biaya_penginapan'    => 'nullable|numeric|min:0',
            'biaya_tip_driver'    => 'nullable|numeric|min:0',
            'biaya_tour_leader'   => 'nullable|numeric|min:0',
            'biaya_parkir'        => 'nullable|numeric|min:0',

            // Variable Standard Costs
            'harga_snack'         => 'nullable|numeric|min:0',
            'jumlah_snack'        => 'nullable|integer|min:0',
            'harga_makan'         => 'nullable|numeric|min:0',
            'jumlah_makan'        => 'nullable|integer|min:0',
            'biaya_asuransi'      => 'nullable|numeric|min:0',

            // Custom Dynamic Costs
            'komponen_fixed'      => 'nullable|array',
            'komponen_fixed.*.nama' => 'required|string',
            'komponen_fixed.*.nominal' => 'required|numeric|min:0',
            'komponen_var'        => 'nullable|array',
            'komponen_var.*.nama'   => 'required|string',
            'komponen_var.*.nominal' => 'required|numeric|min:0',
            'margin_per_pax'      => 'required|numeric|min:0',
        ]);

        // Validation Rules Check: Validasi Kapasitas Bus vs Peserta
        $armada = Armada::findOrFail($validated['armada_id']);
        $totalKapasitas = $armada->kapasitas * $validated['jumlah_unit_armada'];
        if ($totalKapasitas < $validated['jumlah_peserta']) {
            return back()->withInput()->with('error', "Kapasitas unit armada ({$totalKapasitas} seat) tidak mencukupi untuk jumlah peserta ({$validated['jumlah_peserta']} pax).");
        }

        DB::beginTransaction();
        try {
            $peserta = $validated['jumlah_peserta'];

            // 1. Hitung Sewa Base Armada + Sewa Tambahan Menginap
            $sewaBaseArmada = $armada->harga_sewa * $validated['jumlah_unit_armada'];
            $sewaTambahanArmada = ($validated['is_menginap'] === 'ya') ? ($validated['biaya_sewa_tambahan'] ?? 0) : 0;
            $totalBiayaArmada = $sewaBaseArmada + $sewaTambahanArmada;

            // 2. Hitung Tol
            $totalBiayaTol = 0;
            if (!empty($validated['rute_tol_ids'])) {
                $totalBiayaTol = RuteTol::whereIn('id', $validated['rute_tol_ids'])->sum('tarif_total');
            }

            // 3. Total Fixed Costs
            $biayaBbm = ($armada->is_include_solar === 'tidak') ? ($validated['biaya_bbm'] ?? 0) : 0;
            $biayaPenginapan = ($validated['is_menginap'] === 'ya') ? ($validated['biaya_penginapan'] ?? 0) : 0;

            $stdFixed = $biayaBbm + $biayaPenginapan + ($validated['biaya_tip_driver'] ?? 0) + ($validated['biaya_tour_leader'] ?? 0) + ($validated['biaya_parkir'] ?? 0);

            $customFixed = 0;
            if (!empty($validated['komponen_fixed'])) {
                foreach ($validated['komponen_fixed'] as $item) {
                    $customFixed += $item['nominal'];
                }
            }

            $totalBiayaFixed  = $totalBiayaArmada + $totalBiayaTol + $stdFixed + $customFixed;
            $biayaFixedPerPax = $totalBiayaFixed / $peserta;

            // 4. Variable Costs (HTM + Snack + Makan + Asuransi + Custom Var)
            $htmPerPax = Destinasi::whereIn('id', $validated['destinasi_ids'])->sum('htm_per_orang');

            $totalSnackPerPax = ($validated['harga_snack'] ?? 0) * ($validated['jumlah_snack'] ?? 0);
            $totalMakanPerPax = ($validated['harga_makan'] ?? 0) * ($validated['jumlah_makan'] ?? 0);
            $stdVar = $totalSnackPerPax + $totalMakanPerPax + ($validated['biaya_asuransi'] ?? 0);

            $customVar = 0;
            if (!empty($validated['komponen_var'])) {
                foreach ($validated['komponen_var'] as $item) {
                    $customVar += $item['nominal'];
                }
            }

            $biayaVarPerPax = $htmPerPax + $stdVar + $customVar;
            $marginPerPax   = $validated['margin_per_pax'];
            $hargaAkhirPerPax = $biayaFixedPerPax + $biayaVarPerPax + $marginPerPax;

            // 5. Susun String Fasilitas Otomatis
            $fasilitasArr = ['Dokumentasi', 'P3K'];
            if ($armada) {
                array_unshift($fasilitasArr, 'Bus Pariwisata');
            }
            if (!empty($validated['destinasi_ids'])) {
                $fasilitasArr[] = 'Tiket Masuk';
            }
            if (!empty($validated['biaya_tour_leader']) && $validated['biaya_tour_leader'] > 0) {
                $fasilitasArr[] = 'Tour leader/guide';
            }
            if (!empty($validated['jumlah_makan']) && $validated['jumlah_makan'] > 0 && ($validated['harga_makan'] ?? 0) > 0) {
                $fasilitasArr[] = 'Makan ' . $validated['jumlah_makan'] . 'x';
            }
            if (!empty($validated['jumlah_snack']) && $validated['jumlah_snack'] > 0 && ($validated['harga_snack'] ?? 0) > 0) {
                $fasilitasArr[] = 'Snack ' . $validated['jumlah_snack'] . 'x';
            }
            if (!empty($validated['biaya_asuransi']) && $validated['biaya_asuransi'] > 0) {
                $fasilitasArr[] = 'Asuransi Perjalanan';
            }
            $fasilitasStr = implode("\n", array_unique($fasilitasArr));

            $kodeProposal = 'PROP-' . date('Ym') . '-' . strtoupper(Str::random(4));

            $proposal = Proposal::create([
                'kode_proposal'       => $kodeProposal,
                'user_id'             => Auth::id() ?? 1,
                'jumlah_hari'         => $validated['jumlah_hari'],
                'jumlah_malam'        => $validated['jumlah_malam'],
                'is_menginap'         => $validated['is_menginap'],
                'jumlah_peserta'     => $peserta,
                'fasilitas'           => $fasilitasStr,
                'total_biaya_fixed'   => $totalBiayaFixed,
                'biaya_fixed_per_pax' => $biayaFixedPerPax,
                'biaya_var_per_pax'   => $biayaVarPerPax,
                'margin_per_pax'      => $marginPerPax,
                'harga_akhir_per_pax' => $hargaAkhirPerPax,
                'status'              => 'draft',
            ]);

            // Save Relasi Destinasi
            foreach (Destinasi::whereIn('id', $validated['destinasi_ids'])->get() as $dest) {
                ProposalDestinasi::create([
                    'proposal_id'  => $proposal->id,
                    'destinasi_id' => $dest->id,
                    'htm_snapshot' => $dest->htm_per_orang,
                ]);
            }

            // Save Relasi Tol
            if (!empty($validated['rute_tol_ids'])) {
                foreach (RuteTol::whereIn('id', $validated['rute_tol_ids'])->get() as $tol) {
                    ProposalTol::create([
                        'proposal_id'    => $proposal->id,
                        'rute_tol_id'    => $tol->id,
                        'tarif_snapshot' => $tol->tarif_total,
                    ]);
                }
            }

            // Save Relasi Armada
            ProposalArmada::create([
                'proposal_id'   => $proposal->id,
                'armada_id'     => $armada->id,
                'jumlah_unit'   => $validated['jumlah_unit_armada'],
                'sewa_snapshot' => $armada->harga_sewa,
            ]);

            // Save Component Breakdown
            $stdFixedList = [
                'Tambahan Sewa Armada' => $sewaTambahanArmada,
                'BBM / Solar'          => $biayaBbm,
                'Biaya Penginapan'     => $biayaPenginapan,
                'Tip Supir & Co-Driver' => $validated['biaya_tip_driver'] ?? 0,
                'Tour Leader / Guide'  => $validated['biaya_tour_leader'] ?? 0,
                'Biaya Parkir'         => $validated['biaya_parkir'] ?? 0,
            ];
            foreach ($stdFixedList as $nama => $nom) {
                if ($nom > 0) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $nama,
                        'kategori'      => 'fixed',
                        'nominal_satuan' => $nom,
                    ]);
                }
            }

            if (!empty($validated['komponen_fixed'])) {
                foreach ($validated['komponen_fixed'] as $item) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $item['nama'],
                        'kategori'      => 'fixed',
                        'nominal_satuan' => $item['nominal'],
                    ]);
                }
            }

            $stdVarList = [
                'Snack (' . ($validated['jumlah_snack'] ?? 0) . 'x)' => $totalSnackPerPax,
                'Konsumsi / Makan (' . ($validated['jumlah_makan'] ?? 0) . 'x)' => $totalMakanPerPax,
                'Asuransi Perjalanan'  => $validated['biaya_asuransi'] ?? 0,
            ];
            foreach ($stdVarList as $nama => $nom) {
                if ($nom > 0) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $nama,
                        'kategori'      => 'variable',
                        'nominal_satuan' => $nom,
                    ]);
                }
            }

            if (!empty($validated['komponen_var'])) {
                foreach ($validated['komponen_var'] as $item) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $item['nama'],
                        'kategori'      => 'variable',
                        'nominal_satuan' => $item['nominal'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.proposal.show', $proposal->uuid)
                ->with('success', 'Proposal perjalanan berhasil dibuat dengan kode ' . $kodeProposal);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat proposal: ' . $e->getMessage());
        }
    }

    public function show(Proposal $proposal)
    {
        $proposal->load([
            'user',
            'proposalDestinasi.destinasi',
            'proposalTol.ruteTol',
            'proposalArmada.armada',
            'proposalBiayaKomponen'
        ]);

        return view('admin.proposal.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        $proposal->load([
            'proposalDestinasi',
            'proposalTol',
            'proposalArmada',
            'proposalBiayaKomponen'
        ]);

        $destinasiList = Destinasi::orderBy('nama_destinasi')->get();
        $ruteTolList   = RuteTol::orderBy('nama_rute')->get();
        $armadaList    = Armada::orderBy('jenis_armada')->get();

        $selectedDestinasi = $proposal->proposalDestinasi->pluck('destinasi_id')->toArray();
        $selectedTol       = $proposal->proposalTol->pluck('rute_tol_id')->toArray();
        $selectedArmada    = $proposal->proposalArmada->first();

        return view('admin.proposal.edit', compact(
            'proposal',
            'destinasiList',
            'ruteTolList',
            'armadaList',
            'selectedDestinasi',
            'selectedTol',
            'selectedArmada'
        ));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'is_menginap'         => 'required|in:ya,tidak',
            'jumlah_hari'         => 'required|integer|min:1',
            'jumlah_malam'        => 'required|integer|min:0',
            'jumlah_peserta'      => 'required|integer|min:1',
            'armada_id'          => 'required|exists:armada,id',
            'jumlah_unit_armada'  => 'required|integer|min:1',
            'destinasi_ids'      => 'required|array|min:1',
            'destinasi_ids.*'    => 'exists:destinasi,id',
            'rute_tol_ids'       => 'nullable|array',
            'rute_tol_ids.*'     => 'exists:rute_tol,id',

            'biaya_sewa_tambahan' => 'nullable|numeric|min:0',
            'biaya_bbm'           => 'nullable|numeric|min:0',
            'biaya_penginapan'    => 'nullable|numeric|min:0',
            'biaya_tip_driver'    => 'nullable|numeric|min:0',
            'biaya_tour_leader'   => 'nullable|numeric|min:0',
            'biaya_parkir'        => 'nullable|numeric|min:0',

            'harga_snack'         => 'nullable|numeric|min:0',
            'jumlah_snack'        => 'nullable|integer|min:0',
            'harga_makan'         => 'nullable|numeric|min:0',
            'jumlah_makan'        => 'nullable|integer|min:0',
            'biaya_asuransi'      => 'nullable|numeric|min:0',

            'komponen_fixed'      => 'nullable|array',
            'komponen_fixed.*.nama' => 'required|string',
            'komponen_fixed.*.nominal' => 'required|numeric|min:0',
            'komponen_var'        => 'nullable|array',
            'komponen_var.*.nama'   => 'required|string',
            'komponen_var.*.nominal' => 'required|numeric|min:0',
            'margin_per_pax'      => 'required|numeric|min:0',
            'status'              => 'required|in:draft,sent,approved,rejected',
        ]);

        $armada = Armada::findOrFail($validated['armada_id']);
        $totalKapasitas = $armada->kapasitas * $validated['jumlah_unit_armada'];
        if ($totalKapasitas < $validated['jumlah_peserta']) {
            return back()->withInput()->with('error', "Kapasitas unit armada ({$totalKapasitas} seat) tidak mencukupi untuk jumlah peserta ({$validated['jumlah_peserta']} pax).");
        }

        DB::beginTransaction();
        try {
            $peserta = $validated['jumlah_peserta'];

            $sewaBaseArmada = $armada->harga_sewa * $validated['jumlah_unit_armada'];
            $sewaTambahanArmada = ($validated['is_menginap'] === 'ya') ? ($validated['biaya_sewa_tambahan'] ?? 0) : 0;
            $totalBiayaArmada = $sewaBaseArmada + $sewaTambahanArmada;

            $totalBiayaTol = 0;
            if (!empty($validated['rute_tol_ids'])) {
                $totalBiayaTol = RuteTol::whereIn('id', $validated['rute_tol_ids'])->sum('tarif_total');
            }

            $biayaBbm = ($armada->is_include_solar === 'tidak') ? ($validated['biaya_bbm'] ?? 0) : 0;
            $biayaPenginapan = ($validated['is_menginap'] === 'ya') ? ($validated['biaya_penginapan'] ?? 0) : 0;

            $stdFixed = $biayaBbm + $biayaPenginapan + ($validated['biaya_tip_driver'] ?? 0) + ($validated['biaya_tour_leader'] ?? 0) + ($validated['biaya_parkir'] ?? 0);

            $customFixed = 0;
            if (!empty($validated['komponen_fixed'])) {
                foreach ($validated['komponen_fixed'] as $item) {
                    $customFixed += $item['nominal'];
                }
            }

            $totalBiayaFixed  = $totalBiayaArmada + $totalBiayaTol + $stdFixed + $customFixed;
            $biayaFixedPerPax = $totalBiayaFixed / $peserta;

            $htmPerPax = Destinasi::whereIn('id', $validated['destinasi_ids'])->sum('htm_per_orang');

            $totalSnackPerPax = ($validated['harga_snack'] ?? 0) * ($validated['jumlah_snack'] ?? 0);
            $totalMakanPerPax = ($validated['harga_makan'] ?? 0) * ($validated['jumlah_makan'] ?? 0);
            $stdVar = $totalSnackPerPax + $totalMakanPerPax + ($validated['biaya_asuransi'] ?? 0);

            $customVar = 0;
            if (!empty($validated['komponen_var'])) {
                foreach ($validated['komponen_var'] as $item) {
                    $customVar += $item['nominal'];
                }
            }

            $biayaVarPerPax = $htmPerPax + $stdVar + $customVar;
            $marginPerPax   = $validated['margin_per_pax'];
            $hargaAkhirPerPax = $biayaFixedPerPax + $biayaVarPerPax + $marginPerPax;

            $fasilitasArr = ['Dokumentasi', 'P3K'];
            if ($armada) {
                array_unshift($fasilitasArr, 'Bus Pariwisata');
            }
            if (!empty($validated['destinasi_ids'])) {
                $fasilitasArr[] = 'Tiket Masuk';
            }
            if (!empty($validated['biaya_tour_leader']) && $validated['biaya_tour_leader'] > 0) {
                $fasilitasArr[] = 'Tour leader/guide';
            }
            if (!empty($validated['jumlah_makan']) && $validated['jumlah_makan'] > 0 && ($validated['harga_makan'] ?? 0) > 0) {
                $fasilitasArr[] = 'Makan ' . $validated['jumlah_makan'] . 'x';
            }
            if (!empty($validated['jumlah_snack']) && $validated['jumlah_snack'] > 0 && ($validated['harga_snack'] ?? 0) > 0) {
                $fasilitasArr[] = 'Snack ' . $validated['jumlah_snack'] . 'x';
            }
            if (!empty($validated['biaya_asuransi']) && $validated['biaya_asuransi'] > 0) {
                $fasilitasArr[] = 'Asuransi Perjalanan';
            }
            $fasilitasStr = implode("\n", array_unique($fasilitasArr));

            $proposal->update([
                'jumlah_hari'         => $validated['jumlah_hari'],
                'jumlah_malam'        => $validated['jumlah_malam'],
                'is_menginap'         => $validated['is_menginap'],
                'jumlah_peserta'     => $peserta,
                'fasilitas'           => $fasilitasStr,
                'total_biaya_fixed'   => $totalBiayaFixed,
                'biaya_fixed_per_pax' => $biayaFixedPerPax,
                'biaya_var_per_pax'   => $biayaVarPerPax,
                'margin_per_pax'      => $marginPerPax,
                'harga_akhir_per_pax' => $hargaAkhirPerPax,
                'status'              => $validated['status'],
            ]);

            $proposal->proposalDestinasi()->delete();
            $proposal->proposalTol()->delete();
            $proposal->proposalArmada()->delete();
            $proposal->proposalBiayaKomponen()->delete();

            foreach (Destinasi::whereIn('id', $validated['destinasi_ids'])->get() as $dest) {
                ProposalDestinasi::create([
                    'proposal_id'  => $proposal->id,
                    'destinasi_id' => $dest->id,
                    'htm_snapshot' => $dest->htm_per_orang,
                ]);
            }

            if (!empty($validated['rute_tol_ids'])) {
                foreach (RuteTol::whereIn('id', $validated['rute_tol_ids'])->get() as $tol) {
                    ProposalTol::create([
                        'proposal_id'    => $proposal->id,
                        'rute_tol_id'    => $tol->id,
                        'tarif_snapshot' => $tol->tarif_total,
                    ]);
                }
            }

            ProposalArmada::create([
                'proposal_id'   => $proposal->id,
                'armada_id'     => $armada->id,
                'jumlah_unit'   => $validated['jumlah_unit_armada'],
                'sewa_snapshot' => $armada->harga_sewa,
            ]);

            $stdFixedList = [
                'Tambahan Sewa Armada' => $sewaTambahanArmada,
                'BBM / Solar'          => $biayaBbm,
                'Biaya Penginapan'     => $biayaPenginapan,
                'Tip Supir & Co-Driver' => $validated['biaya_tip_driver'] ?? 0,
                'Tour Leader / Guide'  => $validated['biaya_tour_leader'] ?? 0,
                'Biaya Parkir'         => $validated['biaya_parkir'] ?? 0,
            ];
            foreach ($stdFixedList as $nama => $nom) {
                if ($nom > 0) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $nama,
                        'kategori'      => 'fixed',
                        'nominal_satuan' => $nom,
                    ]);
                }
            }

            if (!empty($validated['komponen_fixed'])) {
                foreach ($validated['komponen_fixed'] as $item) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $item['nama'],
                        'kategori'      => 'fixed',
                        'nominal_satuan' => $item['nominal'],
                    ]);
                }
            }

            $stdVarList = [
                'Snack (' . ($validated['jumlah_snack'] ?? 0) . 'x)' => $totalSnackPerPax,
                'Konsumsi / Makan (' . ($validated['jumlah_makan'] ?? 0) . 'x)' => $totalMakanPerPax,
                'Asuransi Perjalanan'  => $validated['biaya_asuransi'] ?? 0,
            ];
            foreach ($stdVarList as $nama => $nom) {
                if ($nom > 0) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $nama,
                        'kategori'      => 'variable',
                        'nominal_satuan' => $nom,
                    ]);
                }
            }

            if (!empty($validated['komponen_var'])) {
                foreach ($validated['komponen_var'] as $item) {
                    ProposalBiayaKomponen::create([
                        'proposal_id'   => $proposal->id,
                        'nama_komponen' => $item['nama'],
                        'kategori'      => 'variable',
                        'nominal_satuan' => $item['nominal'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.proposal.show', $proposal->uuid)
                ->with('success', 'Proposal perjalanan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui proposal: ' . $e->getMessage());
        }
    }

    public function destroy(Proposal $proposal)
    {
        if ($proposal->status === 'approved') {
            return redirect()->route('admin.proposal.index')
                ->with('error', 'Proposal yang sudah di-approve tidak dapat dihapus!');
        }

        $proposal->delete();

        return redirect()->route('admin.proposal.index')
            ->with('success', 'Proposal berhasil dihapus!');
    }

    public function exportPdf(Proposal $proposal)
    {
        $proposal->load([
            'user',
            'proposalDestinasi.destinasi',
            'proposalTol.ruteTol',
            'proposalArmada.armada',
            'proposalBiayaKomponen'
        ]);

        $pdf = Pdf::loadView('admin.proposal.pdf_template', compact('proposal'))
            ->setPaper('A4', 'portrait');

        $fileName = 'Proposal_' . $proposal->kode_proposal . '.pdf';

        return $pdf->download($fileName);
    }
}
