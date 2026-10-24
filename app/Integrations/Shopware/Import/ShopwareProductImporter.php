<?php

namespace App\Integrations\Shopware\Import;

use App\Domain\AI\Services\ProductEmbeddingService;
use App\Domain\Catalog\Repositories\ProductRepository;
use App\Integrations\Core\ConnectorManager;
use App\Integrations\Shopware\Pagination\ShopwareProductPaginator;
use App\Integrations\Shopware\ShopwareConnector;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use App\Models\Store;

final class ShopwareProductImporter
{
    public function __construct(
        protected ConnectorManager $manager,
        protected ShopwareProductNormalizer $normalizer,
        protected ProductRepository $repository,
        protected ProductEmbeddingService $embeddingService,
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

        $paginator = new ShopwareProductPaginator(
            $connector->products()
        );

        while ($result = $paginator->next()) {

            if ($result->items->isEmpty()) {
                continue;
            }

            $productCollection = $this->normalizer
                ->normalizeCollection($result->items);

            $this->repository->saveMany(
                $store,
                $productCollection
            );

            foreach ($productCollection as $product) {

                $this->embeddingService->index(
                    storeId: $store->id,
                    product: $product,
                );
            }

            /*$this->output->info(
                sprintf(
                    'Imported page %d (%d products)',
                    $result->page,
                    $result->items->count()
                )
            );*/
        }
    }
}
