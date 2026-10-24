<?php

namespace App\Domain\Catalog\Collections;

use App\Domain\Catalog\DTO\ProductData;
use App\Domain\Shared\Collections\TypedCollection;

class ProductCollection extends TypedCollection
{
    protected function type(): string
    {
        return ProductData::class;
    }

    /**
     * @param null $callback
     * @param null $default
     * @return ProductData|null
     */
    public function first($callback = null, $default = null): ?ProductData
    {
        return parent::first($callback, $default);
    }
}
