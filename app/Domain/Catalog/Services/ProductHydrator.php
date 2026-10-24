<?php

namespace App\Domain\Catalog\Services;

use App\Domain\AI\DTO\SearchResult;
use App\Domain\Catalog\DTO\ProductData;
use App\Domain\Catalog\Fallback\ProductFallbackProvider;
use App\Domain\Catalog\Repositories\ProductRepository;

class ProductHydrator
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductFallbackProvider $fallbackProvider,
    ) {}

    /**
     * @param SearchResult[] $results
     * @return ProductData[]
     */
    public function hydrate(
        array $results,
        int|string $storeId,
    ): array {

        if ($results === []) {
            return [];
        }

        /*
         * Extract external IDs from Qdrant.
         */
        $externalIds = [];

        foreach ($results as $result) {
            $externalId = $result->payload['external_id'] ?? null;

            if ($externalId !== null) {
                $externalIds[] = (string) $externalId;
            }
        }

        $externalIds = array_values(
            array_unique($externalIds)
        );

        if ($externalIds === []) {
            return [];
        }

        /*
         * First: local database.
         */
        $products = $this->productRepository->findByExternalIds(
            storeId: $storeId,
            externalIds: $externalIds,
        );

        $productsByExternalId = [];

        foreach ($products as $product) {
            $productsByExternalId[$product->externalId] = $product;
        }

        /*
         * Find products missing from local DB.
         */
        $missingIds = array_values(
            array_diff(
                $externalIds,
                array_keys($productsByExternalId)
            )
        );

        /*
         * Second: Shopware fallback.
         *
         * ONE bulk request instead of one request per product.
         */
        if ($missingIds !== []) {
            $fallbackProducts = $this->fallbackProvider->findByExternalIds(
                storeId: $storeId,
                externalIds: $missingIds,
            );

            foreach ($fallbackProducts as $externalId => $product) {
                $productsByExternalId[$externalId] = $product;
            }
        }

        /*
         * Reconstruct Qdrant ranking.
         */
        $hydrated = [];

        foreach ($results as $result) {
            $externalId = $result->payload['external_id'] ?? null;

            if ($externalId === null) {
                continue;
            }

            $externalId = (string) $externalId;

            if (isset($productsByExternalId[$externalId])) {
                $hydrated[] = $productsByExternalId[$externalId];
            }
        }

        return $hydrated;
    }
}
