<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Read\Entity;

use App\Shared\Domain\Read\View;

final readonly class HotelView implements View
{
    public function __construct(
        private string $hotelId,
        private string $name,
        private string $country,
        private string $city,
        private int $numberOfRooms
    ){
    }

    public function id(): string
    {
        return $this->hotelId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function numberOfRooms(): int
    {
        return $this->numberOfRooms;
    }
}
