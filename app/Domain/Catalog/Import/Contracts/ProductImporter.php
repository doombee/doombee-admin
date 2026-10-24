<?php

namespace App\Domain\Catalog\Import\Contracts;

use App\Domain\Catalog\Import\DTO\ImportResult;

interface ProductImporter
{
    public function import(): ImportResult;
}
