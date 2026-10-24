<?php

namespace App\Integrations\Qwen\Resources;

use App\Integrations\Qwen\QwenConnector;
use App\Integrations\Qwen\Requests\ChatCompletionRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class QwenResource
{
    public function __construct(
        protected QwenConnector $connector,
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function chat(
        array $messages,
        array $tools = [],
    ): array {
        return $this->connector
            ->send(new ChatCompletionRequest(
                messages: $messages,
                tools: $tools,
            ))
            ->json();
    }
}
