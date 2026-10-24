<?php

namespace App\Integrations\Shopware\Mappers;

use App\Integrations\Shopware\Collections\ShopwareCategoryCollection;

class ShopwareCategoryCollectionMapper
{
    public function __construct(
        protected ShopwareCategoryMapper $mapper
    ) {}

    public function map(array $categories): ShopwareCategoryCollection
    {
        $collection = new ShopwareCategoryCollection();

        foreach ($categories as $category) {
            $collection->add(
                $this->mapper->map($category)
            );
        }

        return $collection;
    }
}
