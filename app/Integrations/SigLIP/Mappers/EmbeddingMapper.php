<?php

namespace App\Integrations\SigLIP\Mappers;

use App\Domain\AI\DTO\Embedding;
use Saloon\Http\Response;

class EmbeddingMapper
{
    /**
     * @throws \JsonException
     */
    public function map(Response $response): Embedding
    {
        $data = $response->json();

        return new Embedding(
            vector: $data['embedding'],
            model: 'google/siglip2-base-patch16-224'
        );
    }
}
