<?php

namespace App\Integrations\Qdrant\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DeletePointRequest extends Request implements HasBody
{
    use HasJsonBody;
    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $collection,
        private readonly int|string $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/collections/{$this->collection}/points/delete";
    }

    protected function defaultBody(): array
    {
        return [
            'points' => [
                $this->id,
            ],
        ];
    }
}
