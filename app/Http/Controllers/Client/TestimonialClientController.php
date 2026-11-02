<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialClientController extends Controller
{
    public function store(Request $request, $bookingUuid)
    {
        $booking = Booking::where('uuid', $bookingUuid)
            ->where('user_id', Auth::id())
            ->where('status', 'lunas')
            ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'pesan_testimoni' => 'required|string|max:1000',
            'jabatan_profesi' => 'nullable|string|max:100',
        ]);

        Testimonial::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'pesan_testimoni' => $request->pesan_testimoni,
                'jabatan_profesi' => $request->jabatan_profesi ?? 'Client Rombongan',
                'is_published' => true,
            ]
        );

        return back()->with('success', 'Terima kasih! Ulasan testimoni Anda berhasil dikirim.');
    }
}
