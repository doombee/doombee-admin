<?php

namespace App\Integrations\Shopware\DTO;

final readonly class ShopwareProduct
{
    public function __construct(
        public string $id,
        public string $productNumber,
        public ?string $ean,
        public string $name,
        public ?string $description,
        public bool $active,
        public int $stock,
        public array $price,
        public bool $isVariant,
        public ?string $parentId,
        public ?string $manufacturer,
        public array $categories,
        public array $categoryIds,
        public array $images,
        public array $raw,
    ) {}
}
