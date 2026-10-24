<?php

namespace App\Domain\AI\Tools\Contracts;

use App\Domain\AI\Tools\DTO\ToolDefinition;
use App\Domain\AI\Tools\DTO\ToolResult;

interface ToolInterface
{
    public function definition(): ToolDefinition;

    public function execute(array $arguments, int $storeId): ToolResult;
}
