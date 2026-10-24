<?php

namespace App\Integrations\Shopware\DTO;

class ShopwareCategory
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $parentId,
        public readonly bool $active,
        public readonly array $raw = [],
    ) {}
}
