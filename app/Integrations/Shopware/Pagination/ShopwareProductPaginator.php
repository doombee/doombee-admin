<?php

namespace App\Integrations\Shopware\Pagination;

use App\Integrations\Core\Pagination\AbstractPaginator;
use App\Integrations\Core\Pagination\PageResult;
use App\Integrations\Shopware\Collections\ShopwareProductCollection;
use App\Integrations\Shopware\Resources\ProductResource;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

final class ShopwareProductPaginator extends AbstractPaginator
{
    public function __construct(
        protected ProductResource $resource,
        int $limit = 50,
    ) {
        parent::__construct($limit);
    }

    /**
     * @return PageResult<ShopwareProductCollection>|null
     *
     * @throws \JsonException
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function next(): ?PageResult
    {
        $result = $this->resource->search(
            page: $this->page->number,
            limit: $this->page->limit,
        );

        if ($result->items->isEmpty()) {
            return null;
        }

        $this->advance();

        return $result;
    }
}
