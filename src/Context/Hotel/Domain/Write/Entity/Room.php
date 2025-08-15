<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Entity;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\Entity;

class Room extends Entity
{
    private int $floor;
    private int $number;
    private int $capacity;

    public static function create(Uuid $id, int $floor, int $number, int $capacity): self
    {
        $room = new self($id);
        $room->capacity = $capacity;
        $room->floor = $floor;
        $room->number = $number;

        return $room;
    }

    public function floor(): int
    {
        return $this->floor;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }
}