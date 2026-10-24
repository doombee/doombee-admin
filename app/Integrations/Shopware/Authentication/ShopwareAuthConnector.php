<?php

namespace App\Integrations\Shopware\Authentication;

use Saloon\Http\Connector;

class ShopwareAuthConnector extends Connector
{
    public function __construct(
        private readonly string $baseUrl
    ) {}

    public function resolveBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
    }
}
