<?php

namespace App\Providers;

use App\Database\NeonConnector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('db.connector.pgsql', function () {
            return new NeonConnector();
        });
    }

    public function boot(): void
    {
        // Force HTTPS on Vercel production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}