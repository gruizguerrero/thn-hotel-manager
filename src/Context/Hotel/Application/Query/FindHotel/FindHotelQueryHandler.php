<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use App\Context\Hotel\Domain\Write\Repository\HotelRepository;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;
use App\Shared\Application\Bus\Query\Response;
use App\Shared\Domain\ValueObject\Uuid;

final readonly class FindHotelQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private HotelRepository $hotelRepository,
        private FindHotelQueryResponseConverter $responseConverter
    ){
    }

    public function __invoke(FindHotelQuery $query): Response
    {
        //$hotel = $this->hotelRepository->find($query->hotelId());

        $id = Uuid::generate();
        $name = Name::fromString('NH Collection');
        $city = City::fromString('Madrid');
        $country = Country::fromString('ES');

        $hotel = Hotel::create(
            $id,
            $name,
            $city,
            $country,
        );

        return $this->responseConverter->__invoke($hotel);
    }
}
