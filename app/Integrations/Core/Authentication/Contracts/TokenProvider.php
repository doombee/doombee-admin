<?php

namespace App\Integrations\Core\Authentication\Contracts;

use App\Integrations\Core\Authentication\AccessToken;

interface TokenProvider
{
    public function fetch(): AccessToken;
}
