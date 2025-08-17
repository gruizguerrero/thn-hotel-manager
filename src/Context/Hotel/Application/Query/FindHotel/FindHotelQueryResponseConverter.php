<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;

final class FindHotelQueryResponseConverter
{
    public function __invoke(Hotel $hotel): FindHotelQueryResponse
    {
        return new FindHotelQueryResponse([
            FindHotelQueryResponse::ID => $hotel->id()->value(),
            FindHotelQueryResponse::NAME => $hotel->name()->value(),
            FindHotelQueryResponse::CITY => $hotel->city()->value(),
            FindHotelQueryResponse::COUNTRY => $hotel->country()->value(),
        ]);
    }
}
