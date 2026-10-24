<?php

namespace App\Integrations\Shopware\Import;

use App\Domain\AI\Services\CategoryEmbeddingService;
use App\Domain\Catalog\Collections\CategoryCollection;
use App\Domain\Catalog\Services\CategoryHierarchyBuilder;
use App\Integrations\Core\ConnectorManager;
use App\Integrations\Shopware\Pagination\ShopwareCategoryPaginator;
use App\Integrations\Shopware\ShopwareConnector;
use App\Models\Store;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

final class ShopwareCategoryImporter
{
    public function __construct(
        protected ConnectorManager $manager,
        protected ShopwareCategoryNormalizer $normalizer,
        protected CategoryHierarchyBuilder $hierarchyBuilder,
        protected CategoryEmbeddingService $embeddingService
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function import(Store $store): void
    {
        $connection = $store
            ->connections()
            ->firstOrFail();

        /** @var ShopwareConnector $connector */
        $connector = $this->manager
            ->connection($connection);

        $paginator = new ShopwareCategoryPaginator(
            $connector->categories()
        );

        /*
          * Keep all categories in memory.
          */
        $categories = new CategoryCollection();

        while ($result = $paginator->next()) {

            if ($result->items->isEmpty()) {
                continue;
            }

            $categoryCollection = $this->normalizer
                ->normalizeCollection($result->items);

            foreach ($categoryCollection as $category) {
                $categories->add($category);
            }
        }

        /*
         * At this point we have the complete
         * category tree available in memory.
         */
        $hierarchy = $this->hierarchyBuilder->build(
            $categories
        );

        /*
         * Embed every category using its complete
         * hierarchical path.
        */
        foreach ($hierarchy as $category) {
            $this->embeddingService->index(
                storeId: $store->id,
                category: $category,
            );
        }
    }
}
