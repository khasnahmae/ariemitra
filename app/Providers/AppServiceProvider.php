<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Booking;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Safe View Composer agar tidak memicu HTTP 500
        View::composer('layouts.admin', function ($view) {
            try {
                if (Schema::hasTable('bookings')) {
                    $pendingBookingCount = Booking::whereIn('status', ['pending', 'pending_verification'])->count();
                    $view->with('pendingBookingCount', $pendingBookingCount);
                } else {
                    $view->with('pendingBookingCount', 0);
                }
            } catch (\Exception $e) {
                $view->with('pendingBookingCount', 0);
            }
        });
    }
}
