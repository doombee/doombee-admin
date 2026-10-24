<?php

namespace App\Integrations\Router\DTO;

class RouterResult
{
    public function __construct(
        public readonly string $agent,
        public readonly float $confidence,
        public readonly array $scores,
        public readonly string $model,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            agent: $data['agent'],
            confidence: (float) $data['confidence'],
            scores: $data['scores'] ?? [],
            model: $data['model'] ?? 'unknown',
        );
    }
}
