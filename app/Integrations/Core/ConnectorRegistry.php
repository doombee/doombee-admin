<?php

namespace App\Integrations\Core;

class ConnectorRegistry
{
    protected array $connectors = [];

    public function register(string $type, string $connector): void
    {
        $this->connectors[$type] = $connector;
    }

    public function resolve(string $type): ?string
    {
        return $this->connectors[$type] ?? null;
    }
}
