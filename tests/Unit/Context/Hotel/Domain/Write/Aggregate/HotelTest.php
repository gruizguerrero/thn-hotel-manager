<?php

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Aggregate;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use App\Context\Hotel\Domain\Write\Entity\HotelRoom;
use App\Context\Hotel\Domain\Write\Event\HotelCreated;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class HotelTest extends TestCase
{
    public function test_hotel_is_created(): void
    {
        $id = Uuid::generate();
        $name = Name::fromString('NH Collection');
        $city = City::fromString('Madrid');
        $country = Country::fromString('ES');

        $hotel = Hotel::create(
            $id,
            $name,
            $city,
            $country,
        );

        $this->assertTrue($id->equalsTo($hotel->id()));
        $this->assertTrue($name->equalsTo($hotel->name()));
        $this->assertTrue($city->equalsTo($hotel->city()));
        $this->assertTrue($country->equalsTo($hotel->country()));
        $this->assertCount(0, $hotel->rooms());
        $this->assertNotNull($hotel->createdAt());
        $this->assertNull($hotel->updatedAt());

        $events = $hotel->pullEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(HotelCreated::class, $events->first());
    }

    public function test_add_room(): void
    {
        $id = Uuid::generate();
        $name = Name::fromString('NH Collection');
        $city = City::fromString('Madrid');
        $country = Country::fromString('ES');

        $hotel = Hotel::create(
            $id,
            $name,
            $city,
            $country,
        );

        $firstRoom = HotelRoom::create(
            Uuid::generate(),
            1,
            101,
            2
        );

        $secondRoom = HotelRoom::create(
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