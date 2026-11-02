<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query = Booking::whereBetween('tanggal_keberangkatan', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->get();

        // 1. Kinerja Keuangan (KPI Eksekutif)
        $totalOmset = $bookings->whereIn('status', ['booked', 'lunas'])->sum('biaya_sewa');
        $totalMasuk = $bookings->whereIn('status', ['booked', 'lunas'])->sum(function ($b) {
            return $b->dp_1 + $b->dp_2;
        });
        $totalPiutang = $bookings->whereIn('status', ['booked', 'lunas'])->sum('sisa_pembayaran');

        $totalTransaksi = $bookings->count();
        $totalLunas = $bookings->where('status', 'lunas')->count();
        $totalBooked = $bookings->where('status', 'booked')->count();
        $totalBatal = $bookings->where('status', 'cancelled')->count();

        $rasioPelunasan = $totalTransaksi > 0 ? round(($totalLunas / $totalTransaksi) * 100, 1) : 0;

        // 2. Data Grafik Bulanan
        $grafikOmset = Booking::whereIn('status', ['booked', 'lunas'])
            ->whereYear('tanggal_keberangkatan', date('Y'))
            ->selectRaw('MONTH(tanggal_keberangkatan) as bulan, SUM(biaya_sewa) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // 3. FITUR BARU: Top Performing Tour Packages (Paket Terlaris & Omset Tertinggi)
        $topPackages = Booking::whereIn('status', ['booked', 'lunas'])
            ->whereBetween('tanggal_keberangkatan', [$startDate, $endDate])
            ->select(
                'tujuan_wisata',
                DB::raw('COUNT(*) as total_pemesanan'),
                DB::raw('SUM(biaya_sewa) as total_omset'),
                DB::raw('SUM(dp_1 + dp_2) as total_pendapatan_real')
            )
            ->groupBy('tujuan_wisata')
            ->orderBy('total_omset', 'desc')
            ->take(5)
            ->get();

        return view('owner.laporan.index', compact(
            'bookings',
            'startDate',
            'endDate',
            'totalOmset',
            'totalMasuk',
            'totalPiutang',
            'totalTransaksi',
            'totalLunas',
            'totalBooked',
            'totalBatal',
            'rasioPelunasan',
            'grafikOmset',
            'topPackages'
        ));
    }
}
