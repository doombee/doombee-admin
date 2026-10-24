<?php

namespace App\Domain\AI\Contracts;

use App\Domain\AI\DTO\Embedding;

interface ImageEmbeddingProvider
{
    public function embed(string $imageUrl): Embedding;
}
