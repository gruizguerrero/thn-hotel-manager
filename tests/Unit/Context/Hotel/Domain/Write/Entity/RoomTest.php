<?php

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Entity;

use App\Context\Hotel\Domain\Write\Entity\Room;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class RoomTest extends TestCase
{
    public function test_room_is_created(): void
    {
        $id = Uuid::generate();
        $floor = 1;
        $number = 101;
        $capacity = 2;

        $room = Room::create($id, $floor, $number, $capacity);

        $this->assertTrue($id->equalsTo($room->id()));
        $this->assertEquals($capacity, $room->capacity());
        $this->assertEquals($floor, $room->floor());
        $this->assertEquals($number, $room->number());
    }
}