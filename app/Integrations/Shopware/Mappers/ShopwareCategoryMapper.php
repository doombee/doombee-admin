<?php

namespace App\Integrations\Shopware\Mappers;

use App\Integrations\Shopware\DTO\ShopwareCategory;

class ShopwareCategoryMapper
{
    public function map(array $data): ShopwareCategory
    {
        return new ShopwareCategory(
            id: $data['id'],
            name: $data['translated']['name']
            ?? $data['name']
            ?? '',
            parentId: $data['parentId'] ?? null,
            active: (bool) ($data['active'] ?? true),
            raw: $data,
        );
    }
}
