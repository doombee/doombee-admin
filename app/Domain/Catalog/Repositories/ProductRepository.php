<?php

namespace App\Domain\Catalog\Repositories;

use App\Domain\Catalog\Collections\ProductCollection;
use App\Domain\Catalog\DTO\ProductData;
use App\Models\Store;

interface ProductRepository
{
    public function save(
        Store $store,
        ProductData $data
    ): void;

    public function saveMany(
        Store $store,
        ProductCollection $products
    ): void;

    /**
     * @param string[] $externalIds
     * @return ProductData[]
     */
    public function findByExternalIds(
        int|string $storeId,
        array $externalIds
    ): array;
}
