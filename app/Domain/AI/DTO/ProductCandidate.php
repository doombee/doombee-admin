<?php

namespace App\Domain\AI\DTO;

final class ProductCandidate
{
    public function __construct(
        public SearchResult $product,
        public float $productScore,
        public ?float $categoryScore,
        public float $finalScore = 0.0,
    ) {}
}
