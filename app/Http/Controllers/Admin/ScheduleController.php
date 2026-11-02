<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('admin.schedule.index');
    }

    // Endpoint API JSON untuk FullCalendar / Kalender Custom
    public function getEvents(Request $request)
    {
        // Hanya ambil pemesanan yang pembayarannya SUDAH DIVERIFIKASI (status booked atau lunas)
        $bookings = Booking::whereIn('status', ['booked', 'lunas'])->get();

        $events = [];

        foreach ($bookings as $b) {
            $isLunas = $b->status === 'lunas';

            $events[] = [
                'id' => $b->id,
                'uuid' => $b->uuid,
                'title' => $b->nama_pemesan . ' (' . $b->tujuan_wisata . ')',
                'start' => $b->tanggal_keberangkatan,
                'invoice' => $b->no_invoice,
                'pemesan' => $b->nama_pemesan,
                'telepon' => $b->telepon_pemesan,
                'tujuan' => $b->tujuan_wisata,
                'status' => strtoupper($b->status),
                'biaya' => 'Rp ' . number_format($b->biaya_sewa, 0, ',', '.'),
                'sisa' => 'Rp ' . number_format($b->sisa_pembayaran, 0, ',', '.'),
                // Warna card sesuai status (Kuning soft = DP/Booked, Hijau soft = Lunas)
                'backgroundColor' => $isLunas ? '#e6f4ea' : '#fff9e6',
                'borderColor' => $isLunas ? '#34a853' : '#fbbc04',
                'textColor' => $isLunas ? '#137333' : '#b06000',
            ];
        }

        return response()->json($events);
    }
}
