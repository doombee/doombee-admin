<?php

namespace App\Domain\AI\Tools\Registry;

use App\Domain\AI\Tools\Contracts\ToolInterface;
use RuntimeException;

class ToolRegistry
{
    /**
     * @var array<string, class-string<ToolInterface>>
     */
    protected array $tools = [];

    public function __construct(
        protected ToolDefinitionResolver $definitionResolver,
    ) {}

    public function register(
        string $name,
        string $toolClass,
    ): void {
        if (! is_a($toolClass, ToolInterface::class, true)) {
            throw new RuntimeException(
                "Tool [{$toolClass}] must implement ToolInterface."
            );
        }

        $this->tools[$name] = $toolClass;
    }

    public function has(string $name): bool
    {
        return isset($this->tools[$name]);
    }

    public function get(string $name): ToolInterface
    {
        if (! isset($this->tools[$name])) {
            throw new RuntimeException(
                "Tool [{$name}] is not registered."
            );
        }

        return app($this->tools[$name]);
    }

    public function definition(string $name): array
    {
        $tool = $this->get($name);

        return $this->definitionResolver
            ->resolve($tool->definition())
            ->toArray();
    }

    public function definitionFor(
        ToolInterface $tool,
    ): array {
        return $this->definitionResolver
            ->resolve(
                $tool->definition()
            )
            ->toArray();
    }

    public function definitions(): array
    {
        return collect($this->tools)
            ->map(fn (string $class) =>
            $this->definitionResolver
                ->resolve(
                    app($class)->definition()
                )
                ->toArray()
            )
            ->values()
            ->all();
    }
}
