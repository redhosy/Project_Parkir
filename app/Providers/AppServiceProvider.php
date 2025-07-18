<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use app\Services\QRCodeService;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(QRCodeService::class, function () {
            return new QRCodeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
