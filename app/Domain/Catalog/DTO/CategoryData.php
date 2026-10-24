<?php

namespace App\Domain\Catalog\DTO;

final readonly class CategoryData
{
    public function __construct(
        public string $externalId,
        public string $name,
        public ?string $parentId,
        public bool $active,
    ) {}
}
