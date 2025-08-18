<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Context\Hotel\Domain\Read\Entity\HotelView;

final class FindHotelQueryResponseConverter
{
    public function __invoke(HotelView $hotel): FindHotelQueryResponse
    {
        return new FindHotelQueryResponse([
            FindHotelQueryResponse::ID => $hotel->id(),
            FindHotelQueryResponse::NAME => $hotel->name(),
            FindHotelQueryResponse::COUNTRY => $hotel->country(),
            FindHotelQueryResponse::CITY => $hotel->city(),
            FindHotelQueryResponse::NUMBER_OF_ROOMS => $hotel->numberOfRooms(),
        ]);
    }
}
