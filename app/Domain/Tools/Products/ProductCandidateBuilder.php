<?php

namespace App\Domain\Tools\Products;

use App\Domain\AI\DTO\CategorySearchResult;
use App\Domain\AI\DTO\ProductCandidate;
use App\Domain\AI\DTO\SearchResult;
use App\Domain\Tools\Categories\CategoryScoreResolver;

final class ProductCandidateBuilder
{
    public function __construct(
        private readonly CategoryScoreResolver $categoryScoreResolver,
    ) {}

    /**
     * @param SearchResult[] $products
     * @param CategorySearchResult[] $categories
     *
     * @return ProductCandidate[]
     */
    public function build(
        array $products,
        array $categories,
    ): array {
        $candidates = [];

        foreach ($products as $product) {

            $categoryIds = $product->payload['category_ids'] ?? [];

            $categoryScore = $this->categoryScoreResolver->resolve(
                categories: $categories,
                categoryIds: $categoryIds,
            );

            $candidates[] = new ProductCandidate(
                product: $product,
                productScore: $product->score,
                categoryScore: $categoryScore?->score,
            );
        }

        return $candidates;
    }
}
