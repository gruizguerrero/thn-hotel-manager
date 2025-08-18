<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Entity;

use App\Context\Hotel\Domain\Write\Entity\ValueObject\RoomNumber;
use App\Context\Hotel\Domain\Write\Entity\ValueObject\Category;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\Entity;

class HotelRoom extends Entity
{
    private RoomNumber $number;
    private Category $category;

    public static function create(
        Uuid $id,
        RoomNumber $number,
        Category $category
    ): self {
        $room = new self($id);
        $room->number = $number;
        $room->category = $category;

        return $room;
    }

    public function number(): RoomNumber
    {
        return $this->number;
    }

    public function category(): Category
    {
        return $this->category;
    }
}
