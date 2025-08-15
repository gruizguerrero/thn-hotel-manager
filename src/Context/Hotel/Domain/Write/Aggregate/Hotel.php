<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Aggregate;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\NumberOfRooms;
use App\Context\Hotel\Domain\Write\Entity\Room;
use App\Context\Hotel\Domain\Write\Entity\Rooms;
use App\Context\Hotel\Domain\Write\Event\HotelCreated;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;
use DateTimeImmutable;

class Hotel extends AggregateRoot
{
    private Name $name;
    private City $city;
    private Country $country;
    private NumberOfRooms $numberOfRooms;

    /** @var Rooms $rooms */
    private $rooms;

    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        Uuid $id,
        Name $name,
        City $city,
        Country $country
    ): self {
        $hotel = new self($id);
        $hotel->name = $name;
        $hotel->city = $city;
        $hotel->country = $country;
        $hotel->rooms = Rooms::createEmpty();
        $hotel->numberOfRooms = new NumberOfRooms($hotel->rooms->count());
        $hotel->createdAt = new DateTimeImmutable();
        $hotel->updatedAt = null;

        $hotel->recordEvent(
            HotelCreated::create(
                $hotel->id(),
                $hotel->name(),
                $hotel->city(),
                $hotel->country(),
            )
        );

        return $hotel;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms->add($room);
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function city(): City
    {
        return $this->city;
    }

    public function country(): Country
    {
        return $this->country;
    }

    public function rooms(): Rooms
    {
        return $this->rooms;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
