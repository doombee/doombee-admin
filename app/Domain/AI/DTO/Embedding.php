<?php

namespace App\Domain\AI\DTO;

final readonly class Embedding
{
    public function __construct(
        public array $vector,
        public string $model,
    ) {}
}
