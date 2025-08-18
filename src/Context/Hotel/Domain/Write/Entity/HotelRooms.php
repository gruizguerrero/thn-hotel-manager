<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Entity;

use App\Shared\Domain\TypedCollection;

class HotelRooms extends TypedCollection
{
    protected function type(): string
    {
        return HotelRoom::class;
    }
}
