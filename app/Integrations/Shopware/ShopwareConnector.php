<?php

namespace App\Integrations\Shopware;

use App\Integrations\Contracts\IntegrationConnector;
use App\Integrations\Core\Authentication\TokenCache;
use App\Integrations\Core\Authentication\TokenManager;
use App\Integrations\Core\BaseConnector;
use App\Integrations\Core\DTO\BaseCredentials;
use App\Integrations\Enums\ConnectorType;
use App\Integrations\Shopware\Authentication\ShopwareAuthConnector;
use App\Integrations\Shopware\Authentication\ShopwareTokenProvider;
use App\Integrations\Shopware\DTO\ShopwareCredentials;
use App\Integrations\Shopware\Resources\CategoryResource;
use App\Integrations\Shopware\Resources\ProductResource;
use Saloon\Http\Auth\TokenAuthenticator;

class ShopwareConnector extends BaseConnector implements IntegrationConnector
{
    protected ?TokenManager     $tokenManager = null;
    protected ?ProductResource  $products = null;
    protected ?CategoryResource $categories = null;

    public static function type(): ConnectorType
    {
        return ConnectorType::Shopware;
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl();
    }

    protected function defaultAuth(): ?TokenAuthenticator
    {
        return new TokenAuthenticator(
            $this->tokenManager()->token()
        );
    }

    public function testConnection(): bool
    {
        return true;
    }

    public function tokenManager(): TokenManager
    {
        return $this->tokenManager ??= new TokenManager(
            cacheKey: sprintf(
                'shopware:%d',
                $this->connection()->id
            ),
            provider: new ShopwareTokenProvider(
                new ShopwareAuthConnector(
                    $this->resolveBaseUrl()
                ),
                $this->credentials()),
            cache: new TokenCache(),
        );
    }

    protected function makeCredentials(): BaseCredentials
    {
        return ShopwareCredentials::from($this->connection());
    }

    public function products(): ProductResource
    {
        return $this->products ??= app(
            ProductResource::class,
            [
                'connector' => $this,
            ]
        );
    }

    public function categories(): CategoryResource
    {
        return $this->categories ??= app(
            CategoryResource::class,
            [
                'connector' => $this,
            ]
        );
    }
}
