<?php

namespace App\Integrations\Contracts;

use App\Integrations\Enums\ConnectorType;

interface IntegrationConnector
{
    public static function type(): ConnectorType;

    public function testConnection(): bool;
}
