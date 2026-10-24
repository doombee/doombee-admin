<?php

namespace App\Domain\AI\Agents\Registry;

use App\Domain\AI\Agents\Contracts\AgentInterface;
use App\Domain\AI\Agents\DTO\AgentContext;
use App\Domain\AI\Tools\Registry\ToolDefinitionResolver;
use App\Domain\AI\Tools\Registry\ToolRegistry;
use RuntimeException;

class AgentRegistry
{
    /**
     * @var array<string, class-string<AgentInterface>>
     */
    protected array $agents = [];

    public function __construct(
        protected ToolRegistry $toolRegistry
    ) {}

    public function register(
        string $name,
        string $agentClass,
    ): void {
        if (! is_a($agentClass, AgentInterface::class, true)) {
            throw new RuntimeException(
                "Agent [{$agentClass}] must implement AgentInterface."
            );
        }

        $this->agents[$name] = $agentClass;

        $agent = app($agentClass);

        foreach ($agent->tools() as $toolClass) {
            $tool = app($toolClass);

            $definition = $tool->definition();

            $this->toolRegistry->register(
                $definition->name,
                $toolClass,
            );
        }
    }

    public function has(string $name): bool
    {
        return isset($this->agents[$name]);
    }

    public function get(string $name): AgentInterface
    {
        if (! isset($this->agents[$name])) {
            throw new RuntimeException(
                "Agent [{$name}] is not registered."
            );
        }

        return app($this->agents[$name]);
    }

    public function all(): array
    {
        return $this->agents;
    }

    public function definitions(string $name): array
    {
        $agent = $this->get($name);

        return collect($agent->tools())
            ->map(function (string $toolClass) {
                $tool = app($toolClass);

                return $this->toolRegistry
                    ->definitionFor($tool);
            })
            ->values()
            ->all();
    }

    public function context(string $name): AgentContext
    {
        $agent = $this->get($name);

        $tools = collect($agent->tools())
            ->map(function (string $toolClass) {
                $tool = app($toolClass);

                return $this->toolRegistry
                    ->definitionFor($tool);
            })
            ->values()
            ->all();

        return new AgentContext(
            name: $name,
            tools: $tools,
        );
    }
}
