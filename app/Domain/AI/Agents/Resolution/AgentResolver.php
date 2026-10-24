<?php

namespace App\Domain\AI\Agents\Resolution;

use App\Domain\AI\Agents\DTO\AgentContext;
use App\Domain\AI\Agents\Registry\AgentRegistry;
use App\Integrations\Router\Contracts\RouterInterface;
use RuntimeException;

class AgentResolver
{
    public function __construct(
        protected RouterInterface $router,
        protected AgentRegistry $agents,
    ) {}

    public function resolve(string $text): AgentContext
    {
        $result = $this->router->route($text);

        if (! $this->agents->has($result->agent)) {
            throw new RuntimeException(
                "Agent [{$result->agent}] is not registered."
            );
        }

        return $this->agents->context(
            $result->agent
        );
    }
}
