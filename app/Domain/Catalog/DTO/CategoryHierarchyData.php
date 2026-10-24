<?php

namespace App\Domain\Catalog\DTO;

final readonly class CategoryHierarchyData
{
    public function __construct(
        public CategoryData $category,
        public string $path,
    ) {}
}
