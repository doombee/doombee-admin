<?php

namespace App\Integrations\Shopware\Mappers;

use App\Integrations\Shopware\DTO\ShopwareProduct;

class ShopwareProductMapper
{
    public function map(array $data): ShopwareProduct
    {
        return new ShopwareProduct(
            id: $data['id'],
            productNumber: $data['productNumber'],
            ean: $data['ean'] ?? null,
            name: $data['translated']['name']
                ?: $data['name']
                    ?: '',
            description: $data['translated']['description']
                ?: $data['description']
                    ?: null,
            active: (bool)($data['active'] ?? false),
            stock: (int)($data['stock'] ?? 0),
            price: $data['price'] ?? [],
            isVariant: !empty($data['parentId']),
            parentId: $data['parentId'] ?? null,
            manufacturer: $this->extractManufacturer($data),
            categories: $this->extractCategories($data),
            categoryIds: $this->extractCategoryIds($data),
            images: $this->extractImages($data),
            raw: $data,
        );
    }

    private function extractManufacturer(array $data): ?string
    {
        return $data['manufacturer']['name'] ?? null;
    }

    private function extractCategories(array $data): array
    {
        $categories = [];
        foreach ($data['categories'] ?? [] as $category) {
            $name = $category['translated']['name'] ?? $category['name'] ?? null;
            if (is_string($name) && $name !== '') {
                $categories[] = $name;
            }
        }
        return $categories;
    }

    private function extractCategoryIds(array $data): array
    {
        $categoryIds = [];

        foreach ($data['categories'] ?? [] as $category) {
            $id = $category['id'] ?? null;

            if (is_string($id) && $id !== '') {
                $categoryIds[] = $id;
            }
        }

        return array_values(array_unique($categoryIds));
    }

    private function extractImages(array $data): array
    {
        $images = [];
        /** Cover image first. */
        $coverUrl = $data['cover']['media']['url'] ?? null;

        if (is_string($coverUrl) && $coverUrl !== '') {
            $images[] = $coverUrl;
        }

        /** Remaining product images. */
        foreach ($data['media'] ?? [] as $media) {
            $url = $media['media']['url'] ?? null;
            if (is_string($url) && $url !== '' && !in_array($url, $images, true)) {
                $images[] = $url;
            }
        }
        return $images;
    }
}
