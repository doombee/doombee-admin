<?php

namespace App\Integrations\Router\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RouteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $text,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/route';
    }

    protected function defaultBody(): array
    {
        return [
            'text' => $this->text,
        ];
    }
}
