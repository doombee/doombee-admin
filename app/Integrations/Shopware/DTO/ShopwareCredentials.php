<?php

namespace App\Integrations\Shopware\DTO;

use App\Integrations\Core\DTO\BaseCredentials;
use App\Models\StoreConnection;
use InvalidArgumentException;

final readonly class ShopwareCredentials extends BaseCredentials
{
    public function __construct(
        public string $clientId,
        public string $clientSecret,
    ) {
    }

    public static function from(StoreConnection $connection): self
    {
        $credentials = $connection->credentials ?? [];

        return new self(
            clientId: self::require($credentials, 'client_id'),
            clientSecret: self::require($credentials, 'client_secret'),
        );
    }

    private static function require(array $credentials, string $key): string
    {
        $value = data_get($credentials, $key);

        if (blank($value)) {
            throw new InvalidArgumentException(
                "Missing Shopware credential [{$key}]."
            );
        }

        return $value;
    }
}
