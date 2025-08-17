<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Context\Hotel\Domain\Write\Repository\HotelRepository;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Bus\Query\Response;

final readonly class FindHotelQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private HotelRepository $hotelRepository,
        private FindHotelQueryResponseConverter $responseConverter
    ){
    }

    public function __invoke(FindHotelQuery $query): Response
    {
        $hotel = $this->hotelRepository->find($query->hotelId());

        return $this->responseConverter->__invoke($hotel);
    }
}
