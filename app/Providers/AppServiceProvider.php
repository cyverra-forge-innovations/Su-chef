<?php

namespace App\Providers;

use App\Database\NeonConnector;
use Illuminate\Support\ServiceProvider;

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
        //
    }
}