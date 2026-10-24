<?php

namespace App\Domain\Catalog\Collections;

use App\Domain\Catalog\DTO\CategoryData;
use App\Domain\Shared\Collections\TypedCollection;

class CategoryCollection extends TypedCollection
{
    protected function type(): string
    {
        return CategoryData::class;
    }

    /**
     * @param null $callback
     * @param null $default
     * @return CategoryData|null
     */
    public function first($callback = null, $default = null): ?CategoryData
    {
        return parent::first($callback, $default);
    }
}
