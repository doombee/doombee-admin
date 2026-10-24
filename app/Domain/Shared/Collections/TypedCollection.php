<?php

namespace App\Domain\Shared\Collections;

use http\Exception\InvalidArgumentException;
use Illuminate\Support\Collection;

abstract class TypedCollection extends Collection
{
    abstract protected function type(): string;

    public function add($item): static
    {
        if (! $item instanceof ($type = $this->type())) {
            throw new InvalidArgumentException(sprintf(
                'Expected instance of %s, got %s.',
                $type,
                get_debug_type($item),
            ));
        }

        return parent::add($item);
    }
}
