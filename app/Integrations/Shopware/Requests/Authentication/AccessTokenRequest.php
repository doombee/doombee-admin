<?php

namespace App\Integrations\Shopware\Requests\Authentication;

use App\Integrations\Shopware\DTO\ShopwareCredentials;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class AccessTokenRequest extends Request implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ShopwareCredentials $credentials
    ) {}

    public function resolveEndpoint(): string
    {
        return '/api/oauth/token';
    }

    protected function defaultBody(): array
    {
        return [
            'grant_type' => 'client_credentials',

            'client_id' => $this->credentials->clientId,

            'client_secret' => $this->credentials->clientSecret,
        ];
    }

    public function defaultConfig(): array
    {
        return [
            'timeout' => 10,
            'connect_timeout' => 5,
        ];
    }
}
