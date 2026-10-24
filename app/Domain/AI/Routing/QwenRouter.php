<?php

namespace App\Domain\AI\Routing;

use App\Domain\AI\Agents\DTO\AgentContext;
use App\Domain\AI\Routing\DTO\RoutingDecision;
use App\Integrations\Qwen\QwenConnector;
use Illuminate\Support\Facades\Log;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class QwenRouter
{
    public function __construct(
        protected QwenConnector $qwen,
    ) {}

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function route(
        string $text,
        AgentContext $context,
    ): RoutingDecision {

        $response = $this->qwen
            ->qwen()
            ->chat(
                messages: $this->messages(
                    text: $text,
                    context: $context,
                ),
                tools: $this->tools(
                    $context,
                ),
            );

        Log::info('Qwen Router', [
            'finish' => data_get(
                $response,
                'choices.0.finish_reason'
            ),
            'content' => data_get(
                $response,
                'choices.0.message.content'
            ),
            'tool' => data_get(
                $response,
                'choices.0.message.tool_calls.0.function.name'
            ),
            'arguments' => data_get(
                $response,
                'choices.0.message.tool_calls.0.function.arguments'
            ),
        ]);

        return $this->parseResponse($response);
    }

    protected function messages(
        string $text,
        AgentContext $context,
    ): array {
        return [
            [
                'role' => 'system',
                'content' => $this->systemPrompt(
                    $context
                ),
            ],
            [
                'role' => 'user',
                'content' => $text,
            ],
        ];
    }

    protected function tools(
        AgentContext $context,
    ): array {
        return collect($context->tools)
            ->map(function (array $tool) {
                return [
                    'type' => 'function',
                    'function' => [
                        'name' => $tool['name'],
                        'description' => implode(
                            "\n\n",
                            array_filter([
                                $tool['description'] ?? null,
                                $tool['documentation'] ?? null,
                            ])
                        ),
                        'parameters' => [
                            'type' => 'object',
                            'properties' => collect(
                                $tool['parameters']
                            )->mapWithKeys(
                                fn (
                                    array $parameter,
                                    string $name
                                ) => [
                                    $name => [
                                        'type' => $parameter['type'],
                                        'description' =>
                                            $parameter['description'],
                                    ],
                                ]
                            )->all(),
                            'required' => collect(
                                $tool['parameters']
                            )
                                ->filter(
                                    fn (array $parameter) =>
                                        $parameter['required'] ?? false
                                )
                                ->keys()
                                ->values()
                                ->all(),
                        ],
                    ],
                ];
            })
            ->values()
            ->all();
    }

    protected function systemPrompt(
        AgentContext $context,
    ): string {
        return <<<PROMPT
You are the routing assistant for the "{$context->name}" agent.

Your job is to decide whether the user's request
should be handled by one of the available tools.

Use a tool when the request clearly matches its purpose.

If no available tool is appropriate, do not call a tool.

In that case the request should be handled by a larger language model.

Never invent tools or arguments.

When you decide to not use a tool, respond ONLY with valid JSON:

{
  "name": "tool_name",
  "arguments": {
    "parameter": "value"
  }
}

The "query" argument must contain ONLY the product name and eventual properties.
PROMPT;
    }

    protected function parseResponse(
        array $response,
    ): RoutingDecision {
        $message = data_get(
            $response,
            'choices.0.message',
            []
        );

        /*
         * 1. Native OpenAI-style tool calls.
         */
        $toolCalls = $message['tool_calls'] ?? [];

        if (! empty($toolCalls)) {
            $call = $toolCalls[0];

            $name = data_get(
                $call,
                'function.name'
            );

            if (! empty($name)) {
                $arguments = json_decode(
                    data_get(
                        $call,
                        'function.arguments',
                        '{}'
                    ),
                    true
                );

                return new RoutingDecision(
                    type: 'tool',
                    tool: $name,
                    arguments: is_array($arguments)
                        ? $arguments
                        : [],
                );
            }
        }

        /*
         * 2. No content means that Qwen did not
         * select a tool.
         */
        $content = data_get(
            $message,
            'content'
        );

        if ($content === null) {
            return new RoutingDecision(
                type: 'llm',
            );
        }

        /*
         * 3. Qwen returned the tool decision
         * as JSON inside message.content.
         */
        $content = trim((string) $content);

        if ($content === '') {
            return new RoutingDecision(
                type: 'llm',
            );
        }

        $content = $this->normalizeToolContent(
            $content
        );

        /*
         * {} means that Qwen explicitly decided
         * that no tool is appropriate.
         */
        if ($content === '{}') {
            return new RoutingDecision(
                type: 'llm',
            );
        }

        $decoded = json_decode(
            $content,
            true
        );

        /*
         * Invalid JSON or an object without a
         * tool name means no usable tool decision.
         */
        if (
            ! is_array($decoded)
            || empty($decoded['name'])
        ) {
            return new RoutingDecision(
                type: 'llm',
            );
        }

        return new RoutingDecision(
            type: 'tool',
            tool: $decoded['name'],
            arguments: is_array(
                $decoded['arguments'] ?? null
            )
                ? $decoded['arguments']
                : [],
        );
    }

    protected function normalizeToolContent(
        string $content,
    ): string {
        $content = trim($content);

        /*
         * Extract JSON object from Markdown/code/text.
         */
        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        /*
         * Qwen occasionally produces escaped
         * underscores such as:
         *
         * search\_products
         */
        $content = str_replace(
            '\_',
            '_',
            $content
        );

        return trim($content);
    }
}
