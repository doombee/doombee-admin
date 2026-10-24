<?php

namespace App\Integrations\Shopware\Import;

use App\Domain\Catalog\Collections\CategoryCollection;
use App\Domain\Catalog\DTO\CategoryData;
use App\Integrations\Shopware\Collections\ShopwareCategoryCollection;
use App\Integrations\Shopware\DTO\ShopwareCategory;

final class ShopwareCategoryNormalizer
{
    public function normalize(
        ShopwareCategory $category
    ): CategoryData {

        return new CategoryData(
            externalId: $category->id,
            name: $category->name,
            parentId: $category->parentId,
            active: $category->active,
        );
    }

    public function normalizeCollection(
        ShopwareCategoryCollection $categories
    ): CategoryCollection
    {
        $collection = new CategoryCollection();

        foreach ($categories as $category) {
            $collection->add(
                $this->normalize($category)
            );
        }

        return $collection;
    }
}
