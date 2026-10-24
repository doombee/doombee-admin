<?php

namespace App\Domain\Tools\Categories;

use App\Domain\AI\Contracts\EmbeddingProvider;
use App\Domain\AI\Contracts\VectorStore;
use App\Domain\AI\DTO\CategorySearchResult;

final class CategorySearchService
{
    public function __construct(
        protected EmbeddingProvider $embeddingProvider,
        protected VectorStore $vectorStore,
    ) {}

    /**
     * @return CategorySearchResult[]
     */
    public function search(
        string $query,
        int|string $storeId,
        int $limit = 10,
    ): array {

        $embedding = $this->embeddingProvider->embed(
            'query: ' . $query
        );

        $results = $this->vectorStore->search(
            collection: 'categories',
            embedding: $embedding,
            vectorName: 'text',
            limit: $limit,
            filter: [
                'must' => [
                    [
                        'key' => 'store_id',
                        'match' => [
                            'value' => $storeId,
                        ],
                    ],
                ],
            ],
        );

        return array_map(
            fn ($result) => new CategorySearchResult(
                externalId: (string) $result->payload['external_id'],
                name: (string) $result->payload['name'],
                parentId: isset($result->payload['parent_id'])
                    ? (string) $result->payload['parent_id']
                    : null,
                path: (string) $result->payload['path'],
                score: $result->score,
            ),
            $results
        );
    }
}
