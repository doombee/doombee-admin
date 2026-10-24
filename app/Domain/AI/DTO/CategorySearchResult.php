<?php

namespace App\Domain\AI\DTO;

final readonly class CategorySearchResult
{
    public function __construct(
        public string $externalId,
        public string $name,
        public ?string $parentId,
        public string $path,
        public float $score,
    ) {}
}
