<?php

namespace App\Providers;

use App\Domain\Catalog\Fallback\ProductFallbackProvider;
use App\Domain\Catalog\Fallback\ShopwareProductFallbackProvider;
use App\Domain\Catalog\Repositories\EloquentProductRepository;
use App\Domain\Catalog\Repositories\ProductRepository;
use App\Integrations\Router\Contracts\RouterInterface;
use App\Integrations\Router\RouterConnector;
use App\Integrations\Router\RouterService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepository::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            RouterInterface::class,
            function () {
                return new RouterService(
                    new RouterConnector()
                );
            }
        );

        $this->app->bind(
            ProductFallbackProvider::class,
            ShopwareProductFallbackProvider::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
