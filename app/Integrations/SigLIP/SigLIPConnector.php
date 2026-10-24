<?php

namespace App\Integrations\SigLIP;

use Saloon\Http\Connector;

class SigLIPConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return config('services.siglip.url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
