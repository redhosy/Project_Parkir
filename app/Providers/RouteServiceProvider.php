<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home'; // Anda bisa ganti ini jika perlu, misal '/admin/dashboard'

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api') // Menerapkan middleware 'api'
                ->prefix('api')     // Menambahkan prefix '/api'
                ->group(base_path('routes/api.php')); // Memuat file routes/api.php

            // Pastikan blok ini ada dan TIDAK dikomentari
            Route::middleware('web') // Menerapkan middleware 'web'
                ->group(base_path('routes/web.php')); // Memuat file routes/web.php
        });
    }
}
