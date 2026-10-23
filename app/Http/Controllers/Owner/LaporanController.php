<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::whereIn('status', ['booked', 'lunas']);

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_keberangkatan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $laporan = $query->latest()->get();
        $totalPendapatan = $laporan->sum('dp_1') + $laporan->sum('dp_2');

        return view('owner.laporan.index', compact('laporan', 'totalPendapatan'));
    }
}
