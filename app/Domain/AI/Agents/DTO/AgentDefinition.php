<?php

namespace App\Domain\AI\Agents\DTO;

class AgentDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly array $keywords = [],
        public readonly ?string $documentation = null,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'documentation' => $this->documentation,
        ];
    }
}
