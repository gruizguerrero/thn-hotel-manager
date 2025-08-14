<?php

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Aggregate;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;
use App\Context\Hotel\Domain\Write\Entity\Room;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class HotelTest extends TestCase
{
    public function test_hotel_is_created(): void
    {
        $id = Uuid::generate();
        $name = 'NH Collection';
        $city = 'Madrid';
        $country = 'Spain';

        $hotel = Hotel::create(
            $id,
            $name,
            $city,
            $country,
        );

        $this->assertTrue($id->equalsTo($hotel->id()));
        $this->assertEquals($name, $hotel->name());
        $this->assertEquals($city, $hotel->city());
        $this->assertEquals($country, $hotel->country());
        $this->assertCount(0, $hotel->rooms());
    }

    public function test_add_room(): void
    {
        $id = Uuid::generate();
        $name = 'NH Collection';
        $city = 'Madrid';
        $country = 'Spain';

        $hotel = Hotel::create(
            $id,
            $name,
            $city,
            $country,
        );

        $firstRoom = Room::create(
            Uuid::generate(),
            1,
            101,
            2
        );

        $secondRoom = Room::create(
            Uuid::generate(),
            1,
            102,
            2
        );

        $hotel->addRoom($firstRoom);
        $hotel->addRoom($secondRoom);

        $this->assertCount(2, $hotel->rooms());
    }
}