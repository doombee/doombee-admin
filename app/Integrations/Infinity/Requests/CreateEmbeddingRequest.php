<?php

namespace App\Integrations\Infinity\Requests;

use Illuminate\Support\Facades\Log;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateEmbeddingRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/embeddings';
    }

    public function __construct(
        protected string $text
    ) {}

    protected function defaultBody(): array
    {
        Log::info('Infinity embedding input', [
            'text' => $this->text,
            'input' => $this->text,
            'length' => strlen($this->text),
        ]);

        return [
            'model' => config('ai.embedding.model'),
            'input' => [
                $this->text
            ],
        ];
    }
}
