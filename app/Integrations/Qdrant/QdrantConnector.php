<?php

namespace App\Integrations\Qdrant;

use Saloon\Http\Connector;

class QdrantConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return config('services.qdrant.url');
    }
}
