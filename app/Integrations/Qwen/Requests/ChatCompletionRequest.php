<?php

namespace App\Integrations\Qwen\Requests;

use Illuminate\Support\Facades\Log;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ChatCompletionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $messages,
        protected array $tools = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v1/chat/completions';
    }

    protected function defaultBody(): array
    {
        //Log::info('Qwen tools', [
        //    'tools' => $this->tools,
        //]);

        $body = [
            'model' => 'Qwen3-0.6B',
            'messages' => $this->messages,
            'tools' => $this->tools,
            'tool_choice' => 'auto',
            'temperature' => 0.0,
            'top_p' => 1.0,
            'max_tokens' => 64,
            'chat_template_kwargs' => [
                'enable_thinking' => false,
            ],
        ];

        return $body;
    }
}
