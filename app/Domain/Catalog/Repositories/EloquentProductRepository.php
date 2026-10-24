<?php

namespace App\Domain\Catalog\Repositories;

use App\Domain\Catalog\Collections\ProductCollection;
use App\Domain\Catalog\DTO\ProductData;
use App\Domain\Models\Product;
use App\Models\Store;

final class EloquentProductRepository implements ProductRepository
{
    public function save(
        Store $store,
        ProductData $data
    ): void {

        Product::updateOrCreate(
            [
                'store_id'    => $store->id,
                'external_id' => $data->externalId,
                'provider'    => $data->provider,
            ],
            [
                'sku'          => $data->sku,
                'name'         => $data->name,
                'description'  => $data->description,
                'active'       => $data->active,
                'price'        => $data->price,
                'currency'     => $data->currency,
                'stock'        => $data->stock,
                'ean'          => $data->ean,
                'manufacturer' => $data->manufacturer,
                'categories'   => $data->categories,
                'images'       => $data->images,
                'attributes'   => $data->attributes,
            ]
        );
    }

    public function saveMany(
        Store $store,
        ProductCollection $products
    ): void {

        foreach ($products as $product) {
            $this->save($store, $product);
        }
    }

    public function findByExternalIds(
        int|string $storeId,
        array $externalIds
    ): array {
        if ($externalIds === []) {
            return [];
        }

        return Product::query()
            ->where('store_id', $storeId)
            ->whereIn('external_id', $externalIds)
            ->get()
            ->map(fn (Product $product) => ProductData::fromModel($product))
            ->all();
    }
}
