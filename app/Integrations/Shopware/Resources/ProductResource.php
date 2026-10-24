<?php

namespace App\Integrations\Shopware\Resources;

use App\Integrations\Core\Pagination\PageResult;
use App\Integrations\Shopware\Collections\ShopwareProductCollection;
use App\Integrations\Shopware\Mappers\ShopwareProductCollectionMapper;
use App\Integrations\Shopware\Requests\Products\SearchProductsRequest;
use App\Integrations\Shopware\ShopwareConnector;
use Illuminate\Support\Facades\Log;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class ProductResource
{
    public function __construct(
        protected ShopwareConnector $connector,
        protected ShopwareProductCollectionMapper $mapper
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     * @return PageResult<ShopwareProductCollection>
     */
    public function search(
        int $page = 1,
        int $limit = 50,
        array $criteria = [],
    ): PageResult {

        $response = $this->connector->send(
            new SearchProductsRequest(
                page: $page,
                limit: $limit,
                criteria: $criteria,
            )
        );

        return new PageResult(
            items: $this->mapper->map(
                $response->json('data')
            ),
            page: $page,
            limit: $limit,
            total: (int) $response->json('total'),
        );
    }

    /**
     * @param array<string> $externalIds
     * @return ShopwareProductCollection
     * @throws \JsonException
     */
    public function findByExternalIds(array $externalIds): ShopwareProductCollection
    {
        if ($externalIds === []) {
            return new ShopwareProductCollection();
        }

        $response = null;

        try {
            $response = $this->connector->send(
                new SearchProductsRequest(
                    page: 1,
                    limit: count($externalIds),
                    criteria: [
                        'filter' => [
                            [
                                'type' => 'equalsAny',
                                'field' => 'id',
                                'value' => array_values($externalIds),
                            ],
                        ],
                    ],
                )
            );
        } catch (FatalRequestException|RequestException $e) {
            Log::error($e);
        }

        return $this->mapper->map(
            $response->json('data')
        );
    }
}
