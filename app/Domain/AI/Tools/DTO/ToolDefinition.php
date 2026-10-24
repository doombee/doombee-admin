<?php

namespace App\Domain\AI\Tools\DTO;

class ToolDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $agent,
        public readonly array $parameters = [],
        public readonly array $returns = [],
        public readonly array $keywords = [],
        public readonly ?string $documentation = null,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'agent' => $this->agent,
            'parameters' => $this->parameters,
            'returns' => $this->returns,
            'keywords' => $this->keywords,
            'documentation' => $this->documentation,
        ];
    }
}
