<?php

namespace App\Providers;

use App\Integrations\Core\ConnectorRegistry;
use App\Integrations\Shopware\ShopwareConnector;
use Illuminate\Support\ServiceProvider;

class ConnectorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ConnectorRegistry::class, function () {
            return new ConnectorRegistry();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(ConnectorRegistry $registry): void
    {
        $registry->register('shopware', ShopwareConnector::class);
    }
}
