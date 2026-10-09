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
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_proposal', 'like', "%{$search}%")
                    ->orWhere('nama_klien', 'like', "%{$search}%");
            });
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
            'nama_klien'        => 'required|string|max:150',
            'tanggal_proposal'  => 'required|date',
            'durasi'            => 'required|string|max:50',
            'jumlah_peserta'    => 'required|integer|min:1',
            'fasilitas'         => 'nullable|string',
            'armada_id'         => 'required|exists:armada,id',
            'jumlah_unit_armada' => 'required|integer|min:1',
            'destinasi_ids'     => 'required|array|min:1',
            'destinasi_ids.*'   => 'exists:destinasi,id',
            'rute_tol_ids'      => 'nullable|array',
            'rute_tol_ids.*'    => 'exists:rute_tol,id',
            'rute_tol_type'     => 'nullable|array',

            // Biaya Standard Fixed
            'biaya_tip_driver'   => 'nullable|numeric|min:0',
            'biaya_tour_leader'  => 'nullable|numeric|min:0',
            'biaya_bbm_parkir'   => 'nullable|numeric|min:0',

            // Biaya Standard Variable (Per Pax)
            'biaya_snack'        => 'nullable|numeric|min:0',
            'biaya_asuransi'     => 'nullable|numeric|min:0',
            'biaya_makan'        => 'nullable|numeric|min:0',

            // Custom Dynamic Costs
            'komponen_fixed'    => 'nullable|array',
            'komponen_fixed.*.nama' => 'required|string',
            'komponen_fixed.*.nominal' => 'required|numeric|min:0',
            'komponen_var'      => 'nullable|array',
            'komponen_var.*.nama'  => 'required|string',
            'komponen_var.*.nominal' => 'required|numeric|min:0',
            'margin_per_pax'    => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $peserta = $validated['jumlah_peserta'];

            // 1. Hitung Armada
            $armada = Armada::findOrFail($validated['armada_id']);
            $totalBiayaArmada = $armada->sewa_inc_solar * $validated['jumlah_unit_armada'];

            // 2. Hitung Tol
            $totalBiayaTol = 0;
            if (!empty($validated['rute_tol_ids'])) {
                $tolItems = RuteTol::whereIn('id', $validated['rute_tol_ids'])->get();
                foreach ($tolItems as $tol) {
                    $type = $request->input("rute_tol_type.{$tol->id}", 'pp');
                    $multiplier = ($type === 'pp') ? 2 : 1;
                    $totalBiayaTol += ($tol->tarif_total * $multiplier);
                }
            }

            // 3. Total Fixed Cost Standard + Custom
            $stdFixed = ($validated['biaya_tip_driver'] ?? 0) + ($validated['biaya_tour_leader'] ?? 0) + ($validated['biaya_bbm_parkir'] ?? 0);
            $customFixed = 0;
            if (!empty($validated['komponen_fixed'])) {
                foreach ($validated['komponen_fixed'] as $item) {
                    $customFixed += $item['nominal'];
                }
            }

            $totalBiayaFixed  = $totalBiayaArmada + $totalBiayaTol + $stdFixed + $customFixed;
            $biayaFixedPerPax = $totalBiayaFixed / $peserta;

            // 4. Variable Cost (HTM + Standard Var + Custom Var)
            $htmPerPax = Destinasi::whereIn('id', $validated['destinasi_ids'])->sum('htm_per_orang');
            $stdVar = ($validated['biaya_snack'] ?? 0) + ($validated['biaya_asuransi'] ?? 0) + ($validated['biaya_makan'] ?? 0);

            $customVar = 0;
            if (!empty($validated['komponen_var'])) {
                foreach ($validated['komponen_var'] as $item) {
                    $customVar += $item['nominal'];
                }
            }

            $biayaVarPerPax = $htmPerPax + $stdVar + $customVar;
            $marginPerPax   = $validated['margin_per_pax'];
            $hargaAkhirPerPax = $biayaFixedPerPax + $biayaVarPerPax + $marginPerPax;

            $kodeProposal = 'PROP-' . date('Ym') . '-' . strtoupper(Str::random(4));

            $proposal = Proposal::create([
                'kode_proposal'       => $kodeProposal,
                'user_id'             => Auth::id() ?? 1,
                'nama_klien'          => $validated['nama_klien'],
                'tanggal_proposal'   => $validated['tanggal_proposal'],
                'durasi'              => $validated['durasi'],
                'jumlah_peserta'     => $peserta,
                'fasilitas'           => $request->fasilitas,
                'total_biaya_fixed'   => $totalBiayaFixed,
                'biaya_fixed_per_pax' => $biayaFixedPerPax,
                'biaya_var_per_pax'   => $biayaVarPerPax,
                'margin_per_pax'      => $marginPerPax,
                'harga_akhir_per_pax' => $hargaAkhirPerPax,
                'status'              => 'draft',
            ]);

            // Save Destinasi
            foreach (Destinasi::whereIn('id', $validated['destinasi_ids'])->get() as $dest) {
                ProposalDestinasi::create([
                    'proposal_id'  => $proposal->id,
                    'destinasi_id' => $dest->id,
                    'htm_snapshot' => $dest->htm_per_orang,
                ]);
            }

            // Save Tol
            if (!empty($validated['rute_tol_ids'])) {
                foreach (RuteTol::whereIn('id', $validated['rute_tol_ids'])->get() as $tol) {
                    $tipe = $request->input("rute_tol_type.{$tol->id}", 'pp');
                    $multiplier = ($tipe === 'pp') ? 2 : 1;
                    ProposalTol::create([
                        'proposal_id'     => $proposal->id,
                        'rute_tol_id'     => $tol->id,
                        'tipe_perjalanan' => $tipe,
                        'tarif_snapshot'  => $tol->tarif_total * $multiplier,
                    ]);
                }
            }

            // Save Armada
            ProposalArmada::create([
                'proposal_id'   => $proposal->id,
                'armada_id'     => $armada->id,
                'jumlah_unit'   => $validated['jumlah_unit_armada'],
                'sewa_snapshot' => $armada->sewa_inc_solar,
            ]);

            // Save Standard Fixed Components
            $stdFixedList = [
                'Tip Supir & Co-Driver' => $validated['biaya_tip_driver'] ?? 0,
                'Tour Leader / Guide'   => $validated['biaya_tour_leader'] ?? 0,
                'BBM, Operasional & Parkir' => $validated['biaya_bbm_parkir'] ?? 0,
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

            // Save Custom Fixed Components
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

            // Save Standard Variable Components
            $stdVarList = [
                'Snack & Air Mineral' => $validated['biaya_snack'] ?? 0,
                'Asuransi Perjalanan'  => $validated['biaya_asuransi'] ?? 0,
                'Konsumsi / Makan'     => $validated['biaya_makan'] ?? 0,
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

            // Save Custom Variable Components
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

        // Buat map Key-Value [rute_tol_id => tipe_perjalanan] dari DB
        $selectedTolType   = $proposal->proposalTol->pluck('tipe_perjalanan', 'rute_tol_id')->toArray();
        $selectedArmada    = $proposal->proposalArmada->first();

        return view('admin.proposal.edit', compact(
            'proposal',
            'destinasiList',
            'ruteTolList',
            'armadaList',
            'selectedDestinasi',
            'selectedTol',
            'selectedTolType',
            'selectedArmada'
        ));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'nama_klien'         => 'required|string|max:150',
            'tanggal_proposal'   => 'required|date',
            'durasi'             => 'required|string|max:50',
            'jumlah_peserta'     => 'required|integer|min:1',
            'fasilitas'          => 'nullable|string',
            'armada_id'          => 'required|exists:armada,id',
            'jumlah_unit_armada' => 'required|integer|min:1',
            'destinasi_ids'      => 'required|array|min:1',
            'destinasi_ids.*'    => 'exists:destinasi,id',
            'rute_tol_ids'       => 'nullable|array',
            'rute_tol_ids.*'     => 'exists:rute_tol,id',
            'rute_tol_type'      => 'nullable|array',

            'biaya_tip_driver'   => 'nullable|numeric|min:0',
            'biaya_tour_leader'  => 'nullable|numeric|min:0',
            'biaya_bbm_parkir'   => 'nullable|numeric|min:0',
            'biaya_snack'        => 'nullable|numeric|min:0',
            'biaya_asuransi'     => 'nullable|numeric|min:0',
            'biaya_makan'        => 'nullable|numeric|min:0',

            'komponen_fixed'     => 'nullable|array',
            'komponen_fixed.*.nama' => 'required|string',
            'komponen_fixed.*.nominal' => 'required|numeric|min:0',
            'komponen_var'       => 'nullable|array',
            'komponen_var.*.nama'   => 'required|string',
            'komponen_var.*.nominal' => 'required|numeric|min:0',
            'margin_per_pax'     => 'required|numeric|min:0',
            'status'             => 'required|in:draft,sent,approved,rejected',
        ]);

        DB::beginTransaction();
        try {
            $peserta = $validated['jumlah_peserta'];

            $armada = Armada::findOrFail($validated['armada_id']);
            $totalBiayaArmada = $armada->sewa_inc_solar * $validated['jumlah_unit_armada'];

            $totalBiayaTol = 0;
            if (!empty($validated['rute_tol_ids'])) {
                foreach (RuteTol::whereIn('id', $validated['rute_tol_ids'])->get() as $tol) {
                    $type = $request->input("rute_tol_type.{$tol->id}", 'pp');
                    $multiplier = ($type === 'pp') ? 2 : 1;
                    $totalBiayaTol += ($tol->tarif_total * $multiplier);
                }
            }

            $stdFixed = ($validated['biaya_tip_driver'] ?? 0) + ($validated['biaya_tour_leader'] ?? 0) + ($validated['biaya_bbm_parkir'] ?? 0);
            $customFixed = 0;
            if (!empty($validated['komponen_fixed'])) {
                foreach ($validated['komponen_fixed'] as $item) {
                    $customFixed += $item['nominal'];
                }
            }

            $totalBiayaFixed  = $totalBiayaArmada + $totalBiayaTol + $stdFixed + $customFixed;
            $biayaFixedPerPax = $totalBiayaFixed / $peserta;

            $htmPerPax = Destinasi::whereIn('id', $validated['destinasi_ids'])->sum('htm_per_orang');
            $stdVar = ($validated['biaya_snack'] ?? 0) + ($validated['biaya_asuransi'] ?? 0) + ($validated['biaya_makan'] ?? 0);

            $customVar = 0;
            if (!empty($validated['komponen_var'])) {
                foreach ($validated['komponen_var'] as $item) {
                    $customVar += $item['nominal'];
                }
            }

            $biayaVarPerPax = $htmPerPax + $stdVar + $customVar;
            $marginPerPax   = $validated['margin_per_pax'];
            $hargaAkhirPerPax = $biayaFixedPerPax + $biayaVarPerPax + $marginPerPax;

            $proposal->update([
                'nama_klien'          => $validated['nama_klien'],
                'tanggal_proposal'   => $validated['tanggal_proposal'],
                'durasi'              => $validated['durasi'],
                'jumlah_peserta'     => $peserta,
                'fasilitas'          => $request->fasilitas,
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
                    $tipe = $request->input("rute_tol_type.{$tol->id}", 'pp');
                    $multiplier = ($tipe === 'pp') ? 2 : 1;
                    ProposalTol::create([
                        'proposal_id'     => $proposal->id,
                        'rute_tol_id'     => $tol->id,
                        'tipe_perjalanan' => $tipe,
                        'tarif_snapshot'  => $tol->tarif_total * $multiplier,
                    ]);
                }
            }

            ProposalArmada::create([
                'proposal_id'   => $proposal->id,
                'armada_id'     => $armada->id,
                'jumlah_unit'   => $validated['jumlah_unit_armada'],
                'sewa_snapshot' => $armada->sewa_inc_solar,
            ]);

            $stdFixedList = [
                'Tip Supir & Co-Driver' => $validated['biaya_tip_driver'] ?? 0,
                'Tour Leader / Guide'   => $validated['biaya_tour_leader'] ?? 0,
                'BBM, Operasional & Parkir' => $validated['biaya_bbm_parkir'] ?? 0,
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
                'Snack & Air Mineral' => $validated['biaya_snack'] ?? 0,
                'Asuransi Perjalanan'  => $validated['biaya_asuransi'] ?? 0,
                'Konsumsi / Makan'     => $validated['biaya_makan'] ?? 0,
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

    public function hitungSimulasi(Request $request)
    {
        $peserta = max(1, (int) $request->input('jumlah_peserta', 1));

        $armadaId = $request->input('armada_id');
        $jumlahUnit = (int) $request->input('jumlah_unit', 1);
        $sewaArmada = 0;
        if ($armadaId) {
            $armada = Armada::find($armadaId);
            $sewaArmada = $armada ? ($armada->sewa_inc_solar * $jumlahUnit) : 0;
        }

        $ruteTolIds = $request->input('rute_tol_ids', []);
        $ruteTolType = $request->input('rute_tol_type', []);
        $totalTol = 0;
        if (!empty($ruteTolIds)) {
            $tolItems = RuteTol::whereIn('id', $ruteTolIds)->get();
            foreach ($tolItems as $tol) {
                $type = $ruteTolType[$tol->id] ?? 'pp';
                $multiplier = ($type === 'pp') ? 2 : 1;
                $totalTol += ($tol->tarif_total * $multiplier);
            }
        }

        $fixedLainnya = (float) $request->input('total_fixed_lainnya', 0);
        $totalFixed = $sewaArmada + $totalTol + $fixedLainnya;
        $fixedPerPax = $totalFixed / $peserta;

        $destinasiIds = $request->input('destinasi_ids', []);
        $totalHtm = 0;
        if (!empty($destinasiIds)) {
            $totalHtm = Destinasi::whereIn('id', $destinasiIds)->sum('htm_per_orang');
        }

        $varLainnyaPerPax = (float) $request->input('total_var_lainnya', 0);
        $totalVarPerPax = $totalHtm + $varLainnyaPerPax;

        $marginPerPax = (float) $request->input('margin_per_pax', 0);
        $hargaAkhirPerPax = $fixedPerPax + $totalVarPerPax + $marginPerPax;

        return response()->json([
            'jumlah_peserta'      => $peserta,
            'total_fixed'         => $totalFixed,
            'biaya_fixed_per_pax' => round($fixedPerPax, 2),
            'biaya_var_per_pax'   => round($totalVarPerPax, 2),
            'margin_per_pax'      => round($marginPerPax, 2),
            'harga_akhir_per_pax' => round($hargaAkhirPerPax, 2),
            'total_estimasi_omset' => round($hargaAkhirPerPax * $peserta, 2)
        ]);
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

        $fileName = 'Proposal_' . Str::slug($proposal->nama_klien) . '_' . $proposal->kode_proposal . '.pdf';

        return $fileName ? $pdf->download($fileName) : $pdf->stream();
    }
}
