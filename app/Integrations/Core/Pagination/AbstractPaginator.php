<?php

namespace App\Integrations\Core\Pagination;

use App\Integrations\Core\Pagination\Contracts\Paginator;

abstract class AbstractPaginator implements Paginator
{
    protected Page $page;

    public function __construct(
        int $limit = 50,
    ) {
        $this->page = new Page(
            number: 1,
            limit: $limit,
        );
    }

    public function rewind(): void
    {
        $this->page = new Page(
            number: 1,
            limit: $this->page->limit,
        );
    }

    protected function advance(): void
    {
        $this->page->next();
    }
}
