<?php

namespace App\Domain\Catalog\Fallback;

use App\Domain\Catalog\DTO\ProductData;
use App\Integrations\Shopware\Import\ShopwareProductNormalizer;
use App\Integrations\Shopware\ShopwareConnector;
use App\Models\Store;

class ShopwareProductFallbackProvider implements ProductFallbackProvider
{
    public function __construct(
        protected ShopwareProductNormalizer $normalizer,
    ) {}

    /**
     * @param array<string> $externalIds
     * @return array<string, ProductData>
     * @throws \JsonException
     */
    public function findByExternalIds(
        int|string $storeId,
        array $externalIds,
    ): array {
        if ($externalIds === []) {
            return [];
        }

        $store = Store::find($storeId);

        if (!$store) {
            return [];
        }

        $connection = $store->connections()
            ->where('provider', 'shopware')
            ->where('enabled', true)
            ->first();

        if (!$connection) {
            return [];
        }

        $connector = app(
            ShopwareConnector::class,
            [
                'connection' => $connection,
            ]
        );

        $products = $connector
            ->products()
            ->findByExternalIds($externalIds);

        $result = [];

        foreach ($products as $product) {
            $data = $this->normalizer->normalize($product);

            $result[$data->externalId] = $data;
        }

        return $result;
    }
}
