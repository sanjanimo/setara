<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Memaksa semua URL asset & route menggunakan HTTPS di environment produksi/staging
        if ($this->app->environment('production', 'staging') || config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
