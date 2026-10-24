<?php

namespace App\Integrations\Shopware\Authentication;

use App\Integrations\Core\Authentication\AccessToken;
use App\Integrations\Core\Authentication\Contracts\TokenProvider;
use App\Integrations\Shopware\DTO\ShopwareCredentials;
use App\Integrations\Shopware\Requests\Authentication\AccessTokenRequest;
use App\Integrations\Shopware\ShopwareConnector;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class ShopwareTokenProvider implements TokenProvider
{
    public function __construct(
        protected ShopwareAuthConnector $connector,
        protected ShopwareCredentials $credentials
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    public function fetch(): AccessToken
    {
        $response = $this->connector->send(
            new AccessTokenRequest($this->credentials)
        );

        return new AccessToken(
            token: $response->json('access_token'),
            expiresIn: $response->json('expires_in'),
            createdAt: new \DateTimeImmutable(),
        );
    }
}
