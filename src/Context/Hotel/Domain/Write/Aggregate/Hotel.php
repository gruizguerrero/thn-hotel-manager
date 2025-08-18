<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Aggregate;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use App\Context\Hotel\Domain\Write\Entity\HotelRoom;
use App\Context\Hotel\Domain\Write\Entity\HotelRooms;
use App\Context\Hotel\Domain\Write\Event\HotelCreated;
use App\Shared\Domain\ValueObject\IntegerValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;
use DateTimeImmutable;

class Hotel extends AggregateRoot
{
    private Name $name;
    private City $city;
    private Country $country;
    /** @var HotelRooms $rooms */
    private $rooms;
    private IntegerValueObject $numberOfRooms;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        Uuid       $id,
        Name       $name,
        City       $city,
        Country    $country,
        HotelRooms $rooms,
    ): self {
        $hotel = new self($id);
        $hotel->name = $name;
        $hotel->city = $city;
        $hotel->country = $country;
        $hotel->createdAt = new DateTimeImmutable();
        $hotel->updatedAt = null;
        $hotel->rooms = $rooms;
        $hotel->numberOfRooms = new IntegerValueObject($hotel->rooms->count());

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

    public function addRoom(HotelRoom $room): void
    {
        $this->rooms->add($room);
        $this->numberOfRooms++;
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

    public function rooms(): HotelRooms
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
