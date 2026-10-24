<?php

namespace App\Domain\AI\Tools\Registry;

use App\Domain\AI\Tools\DTO\ToolDefinition;
use App\Models\AiTool;

class ToolDefinitionResolver
{
    public function resolve(ToolDefinition $definition): ToolDefinition
    {
        $tool = AiTool::query()
            ->where('name', $definition->name)
            ->first();

        if (!$tool) {
            return $definition;
        }

        return new ToolDefinition(
            name: $definition->name,

            description: $tool->description_override
                ?: $definition->description,

            agent: $definition->agent,

            parameters: $definition->parameters,

            returns: $definition->returns,

            keywords: array_values(array_unique([
                ...$definition->keywords,
                ...($tool->additional_keywords ?? []),
            ])),

            documentation: $tool->documentation_override
                ?: $definition->documentation,
        );
    }
}
