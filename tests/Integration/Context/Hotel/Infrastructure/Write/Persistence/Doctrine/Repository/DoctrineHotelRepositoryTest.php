<?php

declare(strict_types=1);

namespace App\Tests\Integration\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\Repository;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use App\Context\Hotel\Domain\Write\Entity\HotelRoom;
use App\Context\Hotel\Domain\Write\Entity\HotelRooms;
use App\Context\Hotel\Domain\Write\Entity\ValueObject\Category;
use App\Context\Hotel\Domain\Write\Entity\ValueObject\RoomNumber;
use App\Context\Hotel\Domain\Write\Repository\HotelRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Exception\AggregateNotFoundException;
use App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;
use App\Tests\Integration\RepositoryTestCase;

final class DoctrineHotelRepositoryTest extends RepositoryTestCase
{
    public function test_it_saves_a_hotel(): void
    {
        $this->expectNotToPerformAssertions();

        $firstRoom = HotelRoom::create(
            Uuid::generate(),
            RoomNumber::fromString('101'),
            Category::DELUXE,
        );

        $secondRoom = HotelRoom::create(
            Uuid::generate(),
            RoomNumber::fromString('101'),
            Category::SUITE,
        );

        $hotel = Hotel::create(
            Uuid::generate(),
            Name::fromString('NH Collection'),
            City::fromString('Madrid'),
            Country::fromString('ES'),
            HotelRooms::create([$firstRoom, $secondRoom])
        );

        $this->repository()->save($hotel);
        $this->em()->flush();
        $this->em()->clear();
    }

    public function test_it_finds_a_hotel(): void
    {
        $expectedHotel = $this->givenASavedHotel();

        $currentHotel = $this->repository()->find($expectedHotel->id());

        $this->assertEquals($expectedHotel->id(), $currentHotel->id());
    }

    public function test_it_throws_exception_when_hotel_not_found(): void
    {
        $this->expectException(AggregateNotFoundException::class);

        $this->repository()->find(Uuid::generate());
    }

    private function givenASavedHotel(): Hotel
    {
        $firstRoom = HotelRoom::create(
            Uuid::generate(),
            RoomNumber::fromString('101'),
            Category::DELUXE,
        );

        $secondRoom = HotelRoom::create(
            Uuid::generate(),
            RoomNumber::fromString('101'),
            Category::SUITE,
        );

        $hotel = Hotel::create(
            Uuid::generate(),
            Name::fromString('NH Collection'),
            City::fromString('Madrid'),
            Country::fromString('ES'),
            HotelRooms::create([$firstRoom, $secondRoom])
        );

        $this->repository()->save($hotel);
        $this->em()->flush();
        $this->em()->clear();

        return $hotel;
    }

    protected function purge(): void
    {
        $this->purgeTables('hotels', 'hotel_rooms', 'hotel_rooms_assignment');
    }

    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . HotelRepository::class);
    }
}
