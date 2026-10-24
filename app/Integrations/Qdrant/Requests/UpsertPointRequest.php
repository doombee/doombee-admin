<?php

namespace App\Integrations\Qdrant\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpsertPointRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::PUT;

    public function __construct(
        private readonly string $collection,
        private readonly int|string $id,
        private readonly array $vectors,
        private readonly array $payload = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/collections/{$this->collection}/points";
    }

    protected function defaultBody(): array
    {
        return [
            'points' => [
                [
                    'id'      => $this->id,
                    'vector'  => $this->vectors,
                    'payload' => $this->payload,
                ],
            ],
        ];
    }
}
