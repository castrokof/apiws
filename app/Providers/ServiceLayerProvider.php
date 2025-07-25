<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PendienteService;
use App\Services\ConfigurationService;

class ServiceLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PendienteService::class, function ($app) {
            return new PendienteService();
        });

        $this->app->singleton(ConfigurationService::class, function ($app) {
            return new ConfigurationService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}