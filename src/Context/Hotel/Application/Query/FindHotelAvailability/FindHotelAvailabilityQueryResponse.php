<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotelAvailability;

use App\Shared\Application\Bus\Query\Response;

final class FindHotelAvailabilityQueryResponse implements Response
{
    public const string ID = 'id';
    public const string NAME = 'name';
    public const string AVAILABLE_ROOMS = 'available_rooms';

    public function __construct(private readonly array $result)
    {

    }

    public function id(): string
    {
        return $this->result[self::ID];
    }

    public function name(): string
    {
        return $this->result[self::NAME];
    }

    public function availableRooms(): array
    {
        return $this->result[self::AVAILABLE_ROOMS];
    }

    public function result(): array
    {
        return $this->result;
    }
}
