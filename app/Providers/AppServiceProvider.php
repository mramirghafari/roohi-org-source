<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit; // 👈 این کلاس Limit است
use Illuminate\Support\Facades\RateLimiter; // 👈 این کلاس RateLimiter است

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
        RateLimiter::for('lbank-api', function () {
            // 5 درخواست در بازه 10 ثانیه
            return Limit::perSecond(10, 5);
        });
    }
}
