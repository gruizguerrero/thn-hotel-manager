<?php

declare(strict_types=1);

namespace App\Context\Hotel\UI\Controller;

use App\Context\Hotel\Application\Query\FindHotel\FindHotelQueryResponse;
use App\Shared\Application\Bus\Query\Response;
use App\Shared\UI\Response\HttpResponseTransformer;

final class HotelResponseTransformer extends HttpResponseTransformer
{
    protected function transformData(Response $queryResponse): array
    {
        if (!$queryResponse instanceof FindHotelQueryResponse) {
            throw new \InvalidArgumentException('Expected ' . FindHotelQueryResponse::class);
        }

        return [
            FindHotelQueryResponse::ID => $queryResponse->id(),
            FindHotelQueryResponse::NAME => $queryResponse->name(),
            FindHotelQueryResponse::CITY => $queryResponse->city(),
            FindHotelQueryResponse::COUNTRY => $queryResponse->country(),
            FindHotelQueryResponse::NUMBER_OF_ROOMS => $queryResponse->numberOfRooms(),
        ];
    }

    protected function transformMetadata(Response $queryResponse): array
    {
        return [];
    }
}
