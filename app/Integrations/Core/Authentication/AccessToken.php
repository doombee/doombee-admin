<?php

namespace App\Integrations\Core\Authentication;

final readonly class AccessToken
{
    public function __construct(
        public string $token,
        public int $expiresIn,
        public \DateTimeImmutable $createdAt,
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function isExpired(int $buffer = 30): bool
    {
        return $this->createdAt
                ->modify("+{$this->expiresIn} seconds")
                ->modify("-{$buffer} seconds")
            <= new \DateTimeImmutable();
    }
}
