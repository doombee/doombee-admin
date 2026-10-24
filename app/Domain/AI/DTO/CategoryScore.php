<?php

namespace App\Domain\AI\DTO;

final readonly class CategoryScore
{
    public function __construct(
        public string $categoryId,
        public float $score,
    ) {}
}
