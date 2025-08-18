<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotelAvailability;

use App\Context\Hotel\Domain\Read\Entity\AvailableRooms;
use App\Context\Hotel\Domain\Read\Entity\HotelView;

final class FindHotelAvailabilityQueryResponseConverter
{
    public function __invoke(HotelView $hotel, AvailableRooms $availableRooms): FindHotelAvailabilityQueryResponse
    {
        return new FindHotelAvailabilityQueryResponse([
            FindHotelAvailabilityQueryResponse::ID => $hotel->id(),
            FindHotelAvailabilityQueryResponse::NAME => $hotel->name(),
            FindHotelAvailabilityQueryResponse::AVAILABLE_ROOMS => array_map(
                static fn($room) => $room->jsonSerialize(),
                $availableRooms->toArray()
            ),
        ]);
    }
}
