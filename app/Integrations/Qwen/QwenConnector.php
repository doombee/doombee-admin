<?php

namespace App\Integrations\Qwen;

use App\Integrations\Qwen\Resources\QwenResource;
use Saloon\Http\Connector;

class QwenConnector extends Connector
{
    protected int $connectTimeout = 10;

    protected int $requestTimeout = 60;

    protected ?QwenResource $qwen = null;

    public function resolveBaseUrl(): string
    {
        return rtrim(
            config('services.qwen.url'),
            '/'
        );
    }

    public function qwen(): QwenResource
    {
        return $this->qwen ??= app(
            QwenResource::class,
            [
                'connector' => $this,
            ]
        );
    }
}
