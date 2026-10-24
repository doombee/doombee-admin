<?php

namespace App\Integrations\SigLIP;

use App\Domain\AI\Contracts\ImageEmbeddingProvider;
use App\Domain\AI\DTO\Embedding;
use App\Integrations\SigLIP\Mappers\EmbeddingMapper;
use App\Integrations\SigLIP\Requests\CreateImageEmbeddingRequest;

class SigLIPEmbeddingProvider implements ImageEmbeddingProvider
{
    public function __construct(
        private readonly SigLIPConnector $connector,
        private readonly EmbeddingMapper $mapper,
    ) {
    }


    public function embed(string $imageUrl): Embedding
    {
        $response = $this->connector->send(
            new CreateImageEmbeddingRequest($imageUrl)
        );

        return $this->mapper->map($response);
    }
}
