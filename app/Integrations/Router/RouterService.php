<?php

namespace App\Integrations\Router;

use App\Integrations\Router\Contracts\RouterInterface;
use App\Integrations\Router\DTO\RouterResult;
use App\Integrations\Router\Requests\RouteRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class RouterService implements RouterInterface
{
    public function __construct(
        protected RouterConnector $connector,
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function route(string $text): RouterResult
    {
        $response = $this->connector->send(
            new RouteRequest($text)
        );

        $result = RouterResult::fromArray(
            $response->json()
        );

        \Log::info('ModernBERT Router', [
            'text' => $text,
            'agent' => $result->agent,
            'confidence' => $result->confidence,
            'model' => $result->model,
            'scores' => $result->scores,
        ]);

        return $result;
    }
}

