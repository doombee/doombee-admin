<?php

namespace App\Integrations\Core\Pagination;

use Illuminate\Support\Collection;

/**
 * @template T
 */
final readonly class PageResult
{
    /**
     * @param Collection<int, T> $items
     */
    public function __construct(
        public mixed $items,
        public int $page,
        public int $limit,
        public int $total,
    ) {
    }

    public function hasNextPage(): bool
    {
        return ($this->page * $this->limit) < $this->total;
    }

    public function nextPage(): int
    {
        return $this->page + 1;
    }
}
