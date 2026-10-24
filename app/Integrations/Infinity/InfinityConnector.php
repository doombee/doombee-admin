<?php

namespace App\Integrations\Infinity;

use Saloon\Http\Connector;

class InfinityConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return config('services.infinity.url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
