<?php

namespace App\Domain\Catalog\Import\DTO;

class ImportResult
{
    public function __construct(
        public readonly int $created,
        public readonly int $updated,
        public readonly int $failed,
    ) {}
}
