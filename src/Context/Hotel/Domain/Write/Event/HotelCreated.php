<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Event;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Event\DomainEvent;

class HotelCreated extends DomainEvent
{
    private const string NAME = 'name';
    private const string CITY = 'city';
    private const string COUNTRY = 'country';

    public static function create(
        Uuid $id,
        Name $name,
        City $city,
        Country $country
    ): self {
        return new self(
            [
                self::AGGREGATE_ROOT_ID => $id->value(),
                self::NAME => $name->value(),
                self::CITY => $city->value(),
                self::COUNTRY => $country->value(),
            ]
        );
    }

    protected static function stringMessageName(): string
    {
        return 'hotel_management.domain_event.hotel.created';
    }

    protected function version(): string
    {
        return '1.0';
    }
}