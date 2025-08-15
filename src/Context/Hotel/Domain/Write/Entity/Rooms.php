<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Entity;

use App\Shared\Domain\TypedCollection;

class Rooms extends TypedCollection
{
    protected function type(): string
    {
        return Room::class;
    }
}
