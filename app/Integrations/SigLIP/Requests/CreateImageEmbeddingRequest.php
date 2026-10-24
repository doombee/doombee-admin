<?php

namespace App\Integrations\SigLIP\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateImageEmbeddingRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $imageUrl,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/embed';
    }

    protected function defaultBody(): array
    {
        return [
            'url' => $this->imageUrl,
        ];
    }


}
