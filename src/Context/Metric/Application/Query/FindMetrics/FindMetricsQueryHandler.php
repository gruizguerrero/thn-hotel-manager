<?php

declare(strict_types=1);

namespace App\Context\Metric\Application\Query\FindMetrics;

use App\Context\Metric\Domain\Read\Repository\HotelUniqueUsersViewRepository;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Bus\Query\Response;

final readonly class FindMetricsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private HotelUniqueUsersViewRepository $hotelUniqueUsersViewRepository,
        private FindMetricsQueryResponseConverter $responseConverter
    ){
    }

    public function __invoke(FindMetricsQuery $query): Response
    {
        $uniqueUsersPerHotel = $this->hotelUniqueUsersViewRepository->findAll();

        return $this->responseConverter->__invoke($uniqueUsersPerHotel);
    }
}
