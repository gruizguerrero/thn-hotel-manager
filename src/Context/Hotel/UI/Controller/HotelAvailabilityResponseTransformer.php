<?php

declare(strict_types=1);

namespace App\Context\Hotel\UI\Controller;

use App\Context\Hotel\Application\Query\FindHotel\FindHotelQueryResponse;
use App\Context\Hotel\Application\Query\FindHotelAvailability\FindHotelAvailabilityQueryResponse;
use App\Shared\Application\Bus\Query\Response;
use App\Shared\UI\Response\HttpResponseTransformer;

final class HotelAvailabilityResponseTransformer extends HttpResponseTransformer
{
    protected function transformData(Response $queryResponse): array
    {
        if (!$queryResponse instanceof FindHotelAvailabilityQueryResponse) {
            throw new \InvalidArgumentException('Expected ' . FindHotelAvailabilityQueryResponse::class);
        }

        return [
            FindHotelAvailabilityQueryResponse::ID => $queryResponse->id(),
            FindHotelAvailabilityQueryResponse::NAME => $queryResponse->name(),
            FindHotelAvailabilityQueryResponse::AVAILABLE_ROOMS => $queryResponse->availableRooms(),
        ];
    }

    protected function transformMetadata(Response $queryResponse): array
    {
        return [];
    }
}
