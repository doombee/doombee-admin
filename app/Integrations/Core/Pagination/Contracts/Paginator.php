<?php

namespace App\Integrations\Core\Pagination\Contracts;

use App\Integrations\Core\Pagination\PageResult;

interface Paginator
{
    public function next(): ?PageResult;

    public function rewind(): void;
}
