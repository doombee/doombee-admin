<?php

namespace App\Integrations\Shopware\Requests\Categories;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SearchCategoriesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected int $page = 1,
        protected int $limit = 50,
        protected array $criteria = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/api/search/category';
    }

    protected function defaultBody(): array
    {
        return array_merge(
            $this->criteria,
            [
                'page' => $this->page,
                'limit' => $this->limit,
            ]
        );
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
