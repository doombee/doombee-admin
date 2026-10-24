<?php

namespace App\Integrations\Shopware\Collections;

use App\Domain\Shared\Collections\TypedCollection;
use App\Integrations\Shopware\DTO\ShopwareCategory;

class ShopwareCategoryCollection extends TypedCollection
{
    protected function type(): string
    {
        return ShopwareCategory::class;
    }

    /**
     * @param null $callback
     * @param null $default
     * @return ShopwareCategory|null
     */
    public function first($callback = null, $default = null): ?ShopwareCategory
    {
        return parent::first($callback, $default);
    }
}
