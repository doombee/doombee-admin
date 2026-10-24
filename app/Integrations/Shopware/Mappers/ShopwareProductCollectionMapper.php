<?php

namespace App\Integrations\Shopware\Mappers;

use App\Integrations\Shopware\Collections\ShopwareProductCollection;
use Illuminate\Support\Collection;

class ShopwareProductCollectionMapper
{
    public function __construct(
        protected ShopwareProductMapper $mapper
    ) {}

    public function map(array $products): ShopwareProductCollection
    {
        $collection = new ShopwareProductCollection();

        foreach ($products as $product) {
            $collection->add(
                $this->mapper->map($product)
            );
        }

        return $collection;
    }
}
