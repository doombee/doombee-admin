<?php

namespace App\Domain\Catalog\DTO;

use App\Domain\Models\Product;

final readonly class ProductData
{
    public function __construct(
        public string $externalId,
        public string $provider,
        public string $sku,
        public string $name,
        public ?string $description,
        public bool $active,
        public float $price,
        public string $currency,
        public int $stock,
        public ?string $ean,
        public ?string $manufacturer,
        public array $categories,
        public array $categoryIds,
        public array $images,
        public array $attributes,
    ) {}

    public static function fromModel(Product $product): self
    {
        return new self(
            externalId: $product->external_id,
            provider: $product->provider,
            sku: $product->sku,
            name: $product->name,
            description: $product->description,
            active: $product->active,
            price: (float) $product->price,
            currency: $product->currency,
            stock: (int) $product->stock,
            ean: $product->ean,
            manufacturer: $product->manufacturer,
            categories: $product->categories ?? [],
            categoryIds: $product->category_ids ?? [],
            images: $product->images ?? [],
            attributes: $product->attributes ?? [],
        );
    }
}
