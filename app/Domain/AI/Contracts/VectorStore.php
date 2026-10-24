<?php

namespace App\Domain\AI\Contracts;

use App\Domain\AI\DTO\Embedding;
use App\Domain\AI\DTO\SearchResult;

interface VectorStore
{
    public function upsert(
        string $collection,
        int|string $id,
        array $vectors,
        array $payload = [],
    ): void;

    /**
     * @return SearchResult[]
     */
    public function search(
        string $collection,
        Embedding $embedding,
        string $vectorName = 'text',
        int $limit = 10,
        array $filter = [],
    ): array;

    public function delete(
        string $collection,
        int|string $id,
    ): void;
}
