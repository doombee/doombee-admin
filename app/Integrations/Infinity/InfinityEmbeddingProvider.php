<?php

namespace App\Integrations\Infinity;

use App\Domain\AI\Contracts\EmbeddingProvider;
use App\Domain\AI\DTO\Embedding;
use App\Integrations\Infinity\Mappers\EmbeddingMapper;
use App\Integrations\Infinity\Requests\CreateEmbeddingRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class InfinityEmbeddingProvider implements EmbeddingProvider
{
    public function __construct(
        private readonly InfinityConnector $connector,
        private readonly EmbeddingMapper $mapper,
    ) {
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function embed(string $text): Embedding
    {
        $response = $this->connector->send(
            new CreateEmbeddingRequest($text)
        );

        return $this->mapper->map($response);
    }
}
