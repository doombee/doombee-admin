<?php

namespace App\Domain\AI\Tools\DTO;

class ToolResult
{
    public function __construct(
        public readonly bool $success,
        public readonly mixed $data = null,
        public readonly ?string $message = null,
        public readonly ?string $error = null,
    ) {}

    public static function success(
        mixed $data = null,
        ?string $message = null,
    ): self {
        return new self(
            success: true,
            data: $data,
            message: $message,
        );
    }

    public static function failure(
        string $error,
        mixed $data = null,
    ): self {
        return new self(
            success: false,
            data: $data,
            error: $error,
        );
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'message' => $this->message,
            'error' => $this->error,
        ];
    }
}
