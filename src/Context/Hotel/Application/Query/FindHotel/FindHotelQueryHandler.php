<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Context\Hotel\Domain\Read\Repository\HotelViewRepository;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Bus\Query\Response;

final readonly class FindHotelQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private FindHotelQueryResponseConverter $responseConverter,
        private HotelViewRepository $hotelViewRepository,
    ){
    }

    public function __invoke(FindHotelQuery $query): Response
    {
        $hotelView = $this->hotelViewRepository->find($query->hotelId()->value());

        return $this->responseConverter->__invoke($hotelView);
    }
}
