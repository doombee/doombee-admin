<?php

namespace App\Integrations\Shopware\Import;

use App\Domain\Catalog\Collections\ProductCollection;
use App\Domain\Catalog\DTO\ProductData;
use App\Integrations\Shopware\Collections\ShopwareProductCollection;
use App\Integrations\Shopware\DTO\ShopwareProduct;

final class ShopwareProductNormalizer
{
    public function normalize(
        ShopwareProduct $product
    ): ProductData {

        return new ProductData(
            externalId: $product->id,
            provider: 'shopware',
            sku: $product->productNumber,
            name: $product->name,
            description: $product->description,
            active: $product->active,
            price: $this->extractPrice($product),
            currency: $this->extractCurrency($product),
            stock: $product->stock,
            ean: $product->ean,
            manufacturer: $product->manufacturer,
            categories: $product->categories,
            categoryIds: $product->categoryIds,
            images: $product->images,
            attributes: [],
        );
    }

    public function normalizeCollection(
        ShopwareProductCollection $products
    ): ProductCollection {

        $collection = new ProductCollection();

        foreach ($products as $product) {
            $collection->add(
                $this->normalize($product)
            );
        }

        return $collection;
    }

    private function extractPrice(
        ShopwareProduct $product
    ): float {

        return (float) (
            $product->price[0]['gross']
            ?? 0
        );
    }


    private function extractCurrency(
        ShopwareProduct $product
    ): string {

        return 'EUR';
    }
}
