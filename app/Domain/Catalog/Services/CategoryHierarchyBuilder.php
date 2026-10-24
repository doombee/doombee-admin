<?php

namespace App\Domain\Catalog\Services;

use App\Domain\Catalog\Collections\CategoryCollection;
use App\Domain\Catalog\DTO\CategoryData;
use App\Domain\Catalog\DTO\CategoryHierarchyData;

final class CategoryHierarchyBuilder
{
    /**
     * @return array<string, CategoryHierarchyData>
     */
    public function build(
        CategoryCollection $categories
    ): array {
        $byId = [];

        foreach ($categories as $category) {
            $byId[$category->externalId] = $category;
        }

        $result = [];

        foreach ($categories as $category) {
            $result[$category->externalId] = new CategoryHierarchyData(
                category: $category,
                path: $this->buildPath(
                    category: $category,
                    byId: $byId,
                ),
            );
        }

        return $result;
    }

    /**
     * @param array<string, CategoryData> $byId
     */
    private function buildPath(
        CategoryData $category,
        array $byId,
    ): string {
        $parts = [];
        $current = $category;
        $visited = [];

        while ($current !== null) {

            if (isset($visited[$current->externalId])) {
                break;
            }

            $visited[$current->externalId] = true;

            array_unshift(
                $parts,
                $current->name
            );

            if ($current->parentId === null) {
                break;
            }

            $current = $byId[$current->parentId] ?? null;
        }

        return implode(' > ', $parts);
    }
}
