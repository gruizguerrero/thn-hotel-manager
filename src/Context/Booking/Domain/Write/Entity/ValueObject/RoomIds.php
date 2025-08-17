<?php

declare(strict_types=1);

namespace App\Context\Booking\Domain\Write\Entity\ValueObject;

use App\Shared\Domain\TypedCollection;

final class RoomIds extends TypedCollection
{
    protected function type(): string
    {
        return RoomId::class;
    }
}
