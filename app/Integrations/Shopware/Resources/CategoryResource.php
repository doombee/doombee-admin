<?php

namespace App\Integrations\Shopware\Resources;

use App\Integrations\Core\Pagination\PageResult;
use App\Integrations\Shopware\Collections\ShopwareCategoryCollection;
use App\Integrations\Shopware\Mappers\ShopwareCategoryCollectionMapper;
use App\Integrations\Shopware\Requests\Categories\SearchCategoriesRequest;
use App\Integrations\Shopware\ShopwareConnector;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class CategoryResource
{
    public function __construct(
        protected ShopwareConnector $connector,
        protected ShopwareCategoryCollectionMapper $mapper,
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     * @return PageResult<ShopwareCategoryCollection>
     */
    public function search(
        int $page = 1,
        int $limit = 50,
        array $criteria = [],
    ): PageResult {

        $response = $this->connector->send(
            new SearchCategoriesRequest(
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
}
