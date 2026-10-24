<?php

namespace App\Integrations\Infinity\Mappers;

use App\Domain\AI\DTO\Embedding;
use Saloon\Http\Response;

class EmbeddingMapper
{
    /**
     * @throws \JsonException
     */
    public function map(Response $response): Embedding
    {
        return new Embedding(
            vector: $response->json('data.0.embedding'),
            model: config('ai.embedding.model'),
        );
    }
}
