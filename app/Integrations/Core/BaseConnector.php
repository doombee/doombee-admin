<?php

namespace App\Integrations\Core;

use App\Integrations\Core\DTO\BaseCredentials;
use App\Models\Store;
use App\Models\StoreConnection;
use Saloon\Http\Connector;

abstract class BaseConnector extends Connector
{
    protected int $connectTimeout = 10;

    protected int $requestTimeout = 30;

    public function __construct(
        protected StoreConnection $connection,
    ) {}

    protected function baseUrl(): string
    {
        return rtrim(
            $this->store()->base_url,
            '/'
        );
    }

    protected function store(): Store
    {
        return $this->connection->store;
    }

    public function connection(): StoreConnection
    {
        return $this->connection;
    }

    protected ?BaseCredentials $credentials = null;

    abstract protected function makeCredentials(): BaseCredentials;

    public function credentials(): BaseCredentials
    {
        return $this->credentials ??= $this->makeCredentials();
    }

    protected function setting(
        string $key,
        mixed $default = null
    ): mixed
    {
        return data_get(
            $this->settings(),
            $key,
            $default
        );
    }

    public function settings(): array
    {
        return $this->connection->settings ?? [];
    }

    protected function logContext(): array
    {
        return [

            'connection_id' => $this->connection->id,

            'store_id' => $this->connection->store_id,

            'connector' => $this->connection->connector,

        ];
    }
}
