<?php

namespace App\Domain\Tools\Categories;

use App\Domain\AI\DTO\CategorySearchResult;
use App\Domain\AI\DTO\CategoryScore;

final class CategoryScoreResolver
{
    /**
     * @param CategorySearchResult[] $categories
     * @param string[] $categoryIds
     */
    public function resolve(
        array $categories,
        array $categoryIds,
    ): ?CategoryScore {

        if ($categories === [] || $categoryIds === []) {
            return null;
        }

        $categoryIds = array_flip($categoryIds);

        $best = null;

        foreach ($categories as $category) {

            if (!isset($categoryIds[$category->externalId])) {
                continue;
            }

            if (
                $best === null ||
                $category->score > $best->score
            ) {
                $best = new CategoryScore(
                    categoryId: $category->externalId,
                    score: $category->score,
                );
            }
        }

        return $best;
    }
}
