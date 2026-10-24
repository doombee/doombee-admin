<?php

namespace App\Domain\Catalog\Fallback;

use App\Domain\Catalog\DTO\ProductData;

interface ProductFallbackProvider
{
    /**
     * @param string[] $externalIds
     * @return ProductData[]
     */
    public function findByExternalIds(
        int|string $storeId,
        array $externalIds,
    ): array;

}
