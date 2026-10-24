<?php

namespace App\Domain\AI\Tools\Execution;

use App\Domain\AI\Tools\DTO\ToolResult;
use App\Domain\AI\Tools\Registry\ToolRegistry;
use InvalidArgumentException;
use Throwable;

class ToolExecutor
{
    public function __construct(
        protected ToolRegistry $registry,
    ) {}

    public function execute(
        string $name,
        int $storeId,
        array $arguments = [],
    ): ToolResult {

        try {
            $tool = $this->registry->get($name);

            $this->validateArguments(
                $name,
                $arguments,
            );

            return $tool->execute($arguments, $storeId);

        } catch (Throwable $e) {
            return ToolResult::failure(
                error: $e->getMessage(),
            );
        }
    }

    protected function validateArguments(
        string $name,
        array $arguments,
    ): void {
        $definition = $this->registry->definition($name);

        foreach ($definition['parameters'] ?? [] as $parameter => $config) {
            if (
                ($config['required'] ?? false)
                && ! array_key_exists($parameter, $arguments)
            ) {
                throw new InvalidArgumentException(
                    "Missing required argument [{$parameter}] "
                    . "for tool [{$name}]."
                );
            }
        }
    }
}
