<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Read\Entity;

use App\Shared\Domain\TypedCollection;

class AvailableRooms extends TypedCollection
{
    protected function type(): string
    {
        return AvailableRoomView::class;
    }
}
