<?php

namespace App\Domain\Tools\Products;

use App\Domain\AI\DTO\ProductCandidate;

final class ProductReranker
{
    private const float PRODUCT_WEIGHT = 0.65;
    private const float CATEGORY_WEIGHT = 0.35;

    /**
     * @param ProductCandidate[] $candidates
     *
     * @return ProductCandidate[]
     */
    public function rerank(array $candidates): array
    {
        foreach ($candidates as $candidate) {

            $categoryScore = $candidate->categoryScore ?? 0.0;

            $finalScore =
                ($candidate->productScore * self::PRODUCT_WEIGHT)
                +
                ($categoryScore * self::CATEGORY_WEIGHT);

            $candidate->finalScore = $finalScore;
        }

        usort(
            $candidates,
            fn (
                ProductCandidate $a,
                ProductCandidate $b
            ) => $b->finalScore <=> $a->finalScore
        );

        return $candidates;
    }
}
