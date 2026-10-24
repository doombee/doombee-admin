<?php

namespace App\Integrations\Qdrant;

use App\Domain\AI\Contracts\VectorStore;
use App\Domain\AI\DTO\Embedding;
use App\Integrations\Qdrant\Mappers\SearchResultMapper;
use App\Integrations\Qdrant\Requests\DeletePointRequest;
use App\Integrations\Qdrant\Requests\SearchPointRequest;
use App\Integrations\Qdrant\Requests\UpsertPointRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class QdrantVectorStore implements VectorStore
{
    public function __construct(
        private readonly QdrantConnector $connector,
        private readonly SearchResultMapper $mapper,
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function upsert(
        string $collection,
        int|string $id,
        array $vectors,
        array $payload = [],
    ): void {

        $this->connector->send(
            new UpsertPointRequest(
                collection: $collection,
                id: $id,
                vectors: $vectors,
                payload: $payload,
            )
        );
    }


    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function search(
        string $collection,
        Embedding $embedding,
        string $vectorName = 'text',
        int $limit = 10,
        array $filter = [],
    ): array {

        $response = $this->connector->send(
            new SearchPointRequest(
                collection: $collection,
                vector: $embedding->vector,
                vectorName: $vectorName,
                limit: $limit,
                filter: $filter,
            )
        );

        return $this->mapper->map($response);
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(
        string $collection,
        int|string $id,
    ): void {
        $this->connector->send(
            new DeletePointRequest(
                collection: $collection,
                id: $id,
            )
        );
    }
}
