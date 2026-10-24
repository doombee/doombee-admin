<?php

namespace App\Domain\AI\Routing\DTO;

class RoutingDecision
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $tool = null,
        public readonly array $arguments = [],
        public readonly ?string $reason = null,
    ) {}

    public function isTool(): bool
    {
        return $this->type === 'tool';
    }

    public function isLlm(): bool
    {
        return $this->type === 'llm';
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'tool' => $this->tool,
            'arguments' => $this->arguments,
            'reason' => $this->reason,
        ];
    }
}
