<?php

namespace App\Domain\AI\Agents\Execution;

use App\Domain\AI\Agents\Resolution\AgentResolver;
use App\Domain\AI\Routing\QwenRouter;
use App\Domain\AI\Tools\DTO\ToolResult;
use App\Domain\AI\Tools\Execution\ToolExecutor;

class AgentRunner
{
    public function __construct(
        protected AgentResolver $agentResolver,
        protected QwenRouter $router,
        protected ToolExecutor $toolExecutor,
    ) {}

    public function run(string $text, int $storeId): ToolResult
    {
        $context = $this->agentResolver->resolve($text);

        $decision = $this->router->route(
            text: $text,
            context: $context,
        );

        if ($decision->type !== 'tool') {
            return ToolResult::failure(
                error: 'No tool selected.',
            );
        }

        return $this->toolExecutor->execute(
            name: $decision->tool,
            storeId: $storeId,
            arguments: $decision->arguments,
        );
    }
}
