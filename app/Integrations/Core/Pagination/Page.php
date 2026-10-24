<?php

namespace App\Integrations\Core\Pagination;

final class Page
{
    public function __construct(
        public int $number = 1,
        public int $limit = 50,
    ) {
    }

    public function next(): void
    {
        $this->number++;
    }
}
