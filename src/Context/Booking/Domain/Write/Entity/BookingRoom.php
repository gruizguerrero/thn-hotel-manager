<?php

declare(strict_types=1);

namespace App\Context\Booking\Domain\Write\Entity;

use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomId;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\Entity;

class BookingRoom extends Entity
{
    private RoomId $roomId;

    public static function create(Uuid $id, RoomId $roomId): self
    {
        $bookingRoom = new self($id);
        $bookingRoom->roomId = $roomId;

        return $bookingRoom;
    }

    public function roomId(): Uuid
    {
        return $this->roomId;
    }
}
