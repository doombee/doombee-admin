<?php

namespace App\Integrations\Router\Contracts;

use App\Integrations\Router\DTO\RouterResult;

interface RouterInterface
{
    public function route(string $text): RouterResult;
}
