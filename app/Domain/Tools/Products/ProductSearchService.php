<?php

namespace App\Domain\Tools\Products;

use App\Domain\AI\Contracts\EmbeddingProvider;
use App\Domain\AI\Contracts\VectorStore;
use App\Domain\AI\DTO\SearchResult;
use App\Domain\Catalog\Services\ProductHydrator;
use App\Domain\Tools\Categories\CategorySearchService;

class ProductSearchService
{
    public function __construct(
        protected EmbeddingProvider $embeddingProvider,
        protected VectorStore $vectorStore,
        protected ProductHydrator $productHydrator,
        protected CategorySearchService $categorySearchService,
        protected ProductCandidateBuilder $candidateBuilder,
        protected ProductReranker $reranker,
    ) {}

    /**
     * @return SearchResult[]
     */
    public function search(
        string $query,
        int|string $storeId,
        int $limit = 10,
    ): array {

        $embedding = $this->embeddingProvider->embed(
            'query: ' . $query
        );

        $searchResults = $this->vectorStore->search(
            collection: 'products',
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

        /*
         * Search the category space independently.
         */
        $categories = $this->categorySearchService->search(
            query: $query,
            storeId: $storeId,
            limit: 10,
        );

        /*
         * Combine product similarity with category relevance.
         */
        $candidates = $this->candidateBuilder->build(
            products: $searchResults,
            categories: $categories,
        );

        /*
         * Rerank products using both signals.
         */
        $candidates = $this->reranker->rerank(
            $candidates
        );

        /*
         * Extract the original product SearchResults.
         */
        $searchResults = array_map(
            fn ($candidate) => $candidate->product,
            $candidates
        );

        return $this->productHydrator->hydrate(
            $searchResults,
            $storeId,
        );
    }
}
