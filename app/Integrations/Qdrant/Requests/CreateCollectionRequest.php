<?php

namespace App\Integrations\Qdrant\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateCollectionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        private readonly string $collection,
        private readonly int $dimensions,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/collections/{$this->collection}";
    }

    protected function defaultBody(): array
    {
        return [
            'vectors' => [
                'size' => $this->dimensions,
                'distance' => 'Cosine',
            ],
        ];
    }
}
