<?php

declare(strict_types=1);

namespace App\Context\Hotel\UI\Controller;

use App\Context\Hotel\Application\Query\FindHotel\FindHotelQueryResponse;
use App\Shared\Application\Bus\Query\Response;
use App\Shared\UI\Response\HttpResponseTransformer;

class HotelResponseTransformer extends HttpResponseTransformer
{
    protected function transformData(Response $queryResponse): array
    {
        if (!$queryResponse instanceof FindHotelQueryResponse) {
            throw new \InvalidArgumentException('Expected ' . FindHotelQueryResponse::class);
        }

        return [
            'id' => $queryResponse->id(),
            'name' => $queryResponse->name(),
            'city' => $queryResponse->city(),
            'country' => $queryResponse->country(),
        ];
    }

    protected function transformMetadata(Response $queryResponse): array
    {
        return [];
    }
}
