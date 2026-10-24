<?php

namespace App\Integrations\Core;

use App\Models\StoreConnection;
use RuntimeException;

class ConnectorManager
{
    public function __construct(
        protected ConnectorRegistry $registry,
    ) {}

    public function connection(StoreConnection $connection)
    {
        $class = $this->registry->resolve(
            $connection->connector->value
        );

        if (! $class) {
            throw new RuntimeException(
                'Connector not registered.'
            );
        }

        return app($class, [
            'connection' => $connection,
        ]);
    }
}
