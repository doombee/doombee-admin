<?php

namespace App\Domain\AI\Contracts;

use App\Domain\AI\DTO\Embedding;

interface EmbeddingProvider
{
    public function embed(string $text): Embedding;
}
