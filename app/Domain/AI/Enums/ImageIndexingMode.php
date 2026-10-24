<?php

namespace App\Domain\AI\Enums;

enum ImageIndexingMode: string
{
    case MAIN_ONLY = 'main_only';
    case ALL = 'all';
}
