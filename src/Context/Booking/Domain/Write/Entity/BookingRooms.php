<?php

declare(strict_types=1);

namespace App\Context\Booking\Domain\Write\Entity;

use App\Shared\Domain\TypedCollection;

class BookingRooms extends TypedCollection
{
    protected function type(): string
    {
        return BookingRoom::class;
    }
}
