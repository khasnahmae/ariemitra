<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Booking;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS di Vercel/Production agar tidak kena Mixed Content
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        View::composer('layouts.admin', function ($view) {
            $pendingBookingCount = Booking::whereIn('status', ['pending', 'pending_verification'])->count();
            $view->with('pendingBookingCount', $pendingBookingCount);
        });
    }
}
