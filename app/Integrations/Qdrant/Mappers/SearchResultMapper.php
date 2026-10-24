<?php

namespace App\Integrations\Qdrant\Mappers;

use App\Domain\AI\DTO\SearchResult;
use Saloon\Http\Response;

class SearchResultMapper
{
    /**
     * @return SearchResult[]
     * @throws \JsonException
     */
    public function map(Response $response): array
    {
        return collect($response->json('result'))
            ->map(
                fn(array $item) => new SearchResult(
                    id: $item['id'],
                    score: $item['score'],
                    payload: $item['payload'] ?? [],
                )
            )
            ->all();
    }
}
