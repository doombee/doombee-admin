<?php

namespace App\Integrations\Router;

use Saloon\Http\Connector;

class RouterConnector extends Connector
{
    protected int $connectTimeout = 10;

    protected int $requestTimeout = 30;

    public function resolveBaseUrl(): string
    {
        return rtrim(
            config('services.router.url'),
            '/'
        );
    }
}
