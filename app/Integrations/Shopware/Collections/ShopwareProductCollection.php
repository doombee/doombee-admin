<?php

namespace App\Integrations\Shopware\Collections;

use App\Domain\Shared\Collections\TypedCollection;
use App\Integrations\Shopware\DTO\ShopwareProduct;

class ShopwareProductCollection extends TypedCollection
{
    protected function type(): string
    {
        return ShopwareProduct::class;
    }

    /**
     * @param null $callback
     * @param null $default
     * @return ShopwareProduct|null
     */
    public function first($callback = null, $default = null): ?ShopwareProduct
    {
        return parent::first($callback, $default);
    }
}
