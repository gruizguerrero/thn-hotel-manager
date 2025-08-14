<?php

namespace App\Context\Hotel\Domain\Write\Aggregate;

use App\Context\Hotel\Domain\Write\Entity\Room;
use App\Context\Hotel\Domain\Write\Entity\Rooms;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;

class Hotel extends AggregateRoot
{
    private string $name;
    private string $city;
    private string $country;
    private int $numberOfRooms;

    /** @var Rooms $rooms */
    private $rooms;

    public static function create(
        Uuid $id,
        string $name,
        string $city,
        string $country
    ): self {
        $hotel = new self($id);
        $hotel->name = $name;
        $hotel->city = $city;
        $hotel->country = $country;
        $hotel->numberOfRooms = 0;
        $hotel->rooms = Rooms::createEmpty();

        return $hotel;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms->add($room);
        $this->numberOfRooms++;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function rooms(): Rooms
    {
        return $this->rooms;
    }
}