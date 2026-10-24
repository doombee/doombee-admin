<?php

namespace App\Domain\AI\Agents\DTO;

use App\Domain\AI\Agents\Contracts\AgentInterface;

class AgentContext
{
    public function __construct(
        public readonly string $name,
        public readonly array $tools,
    ) {}

    public function toArray(): array
    {
        return [
            'agent' => $this->name,
            'tools' => $this->tools,
        ];
    }
}
