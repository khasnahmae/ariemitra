<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return $request->expectsJson() ? null : route('auth');
    // }
    protected function redirectTo(Request $request): ?string
    {
        // Ubah route('auth') menjadi route('login')
        return $request->expectsJson() ? null : route('login');
    }
}
