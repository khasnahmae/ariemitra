<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialAdminController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with(['user', 'booking'])->latest()->get();
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function toggleStatus($uuid)
    {
        $testimonial = Testimonial::where('uuid', $uuid)->firstOrFail();
        $testimonial->is_published = !$testimonial->is_published;
        $testimonial->save();

        return back()->with('success', 'Status publikasi testimoni berhasil diperbarui!');
    }

    public function destroy($uuid)
    {
        $testimonial = Testimonial::where('uuid', $uuid)->firstOrFail();
        $testimonial->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
