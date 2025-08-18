<?php

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Entity;

use App\Context\Hotel\Domain\Write\Entity\HotelRoom;
use App\Context\Hotel\Domain\Write\Entity\ValueObject\Category;
use App\Context\Hotel\Domain\Write\Entity\ValueObject\RoomNumber;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class HotelRoomTest extends TestCase
{
    public function test_hotel_room_is_created(): void
    {
        $id = Uuid::generate();
        $roomNumber = RoomNumber::fromString('101');
        $category = Category::STANDARD;

        $room = HotelRoom::create($id, $roomNumber, $category);

        $this->assertTrue($id->equalsTo($room->id()));
        $this->assertEquals($category, $room->category());
        $this->assertEquals($roomNumber, $room->number());
    }
}
