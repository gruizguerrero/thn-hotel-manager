<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotelAvailability;

use App\Context\Hotel\Domain\Read\Repository\AvailabilityCalendarViewRepository;
use App\Context\Hotel\Domain\Read\Repository\HotelViewRepository;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Bus\Query\Response;

final readonly class FindHotelAvailabilityQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private FindHotelAvailabilityQueryResponseConverter $responseConverter,
        private AvailabilityCalendarViewRepository $availabilityCalendarViewRepository,
        private HotelViewRepository $hotelViewRepository,
    ){
    }

    public function __invoke(FindHotelAvailabilityQuery $query): Response
    {
        $hotelView = $this->hotelViewRepository->find($query->hotelId()->value());

        $availableRooms = $this->availabilityCalendarViewRepository->findAvailability(
            $query->hotelId()->value(),
            $query->fromDate(),
            $query->toDate()
        );

        return $this->responseConverter->__invoke($hotelView, $availableRooms);
    }
}
