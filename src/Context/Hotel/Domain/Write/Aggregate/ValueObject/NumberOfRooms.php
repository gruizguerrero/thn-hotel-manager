<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Shared\Domain\ValueObject\IntegerValueObject;

/** ToDo. Remove it from read model */
final class NumberOfRooms extends IntegerValueObject
{
    public function increment(): self
    {
        return new self($this->value() + 1);
    }
}
