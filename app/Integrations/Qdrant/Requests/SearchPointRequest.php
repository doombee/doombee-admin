<?php

namespace App\Integrations\Qdrant\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SearchPointRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $collection,
        private readonly array $vector,
        private readonly string $vectorName,
        private readonly int $limit = 10,
        private readonly array $filter = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/collections/{$this->collection}/points/search";
    }

    protected function defaultBody(): array
    {
        $body = [
            'vector' => [
                'name' => $this->vectorName,
                'vector' => $this->vector,
            ],
            'limit' => $this->limit,
            'with_payload' => true,
        ];

        if ($this->filter !== []) {
            $body['filter'] = $this->filter;
        }

        return $body;
    }
}
