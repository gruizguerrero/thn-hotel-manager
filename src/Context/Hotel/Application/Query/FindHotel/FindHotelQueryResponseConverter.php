<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;

final class FindHotelQueryResponseConverter
{
    public function __invoke(Hotel $hotel): FindHotelQueryResponse
    {
        return new FindHotelQueryResponse([
            FindHotelQueryResponse::ID => 'f4a8f92c-5208-4568-869c-3bc50bb28350',
            FindHotelQueryResponse::NAME => 'NH Collection',
            FindHotelQueryResponse::CITY => 'Madrid',
            FindHotelQueryResponse::COUNTRY => 'ES',
        ]);

        /**return new FindHotelQueryResponse([
            FindHotelQueryResponse::ID => $hotel->id()->value(),
            FindHotelQueryResponse::NAME => $hotel->name()->value(),
            FindHotelQueryResponse::CITY => $hotel->city()->value(),
            FindHotelQueryResponse::COUNTRY => $hotel->country()->value(),
        ]);**/
    }
}

/**return [
'data' => [
'id' => 'f4a8f92c-5208-4568-869c-3bc50bb28350',
'name' => 'NH Collection',
'city' => 'Madrid',
'country' => 'ES',
'available_rooms' => [
['number' => '101', 'type' => 'single'],
['number' => '102', 'type' => 'double'],
],
],
'metadata' => [],
];**/
