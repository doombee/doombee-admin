<?php

namespace App\Domain\AI\DTO;

final readonly class SearchResult
{
    public function __construct(
        public int|string $id,
        public float $score,
        public array $payload,
    ) {}
}
