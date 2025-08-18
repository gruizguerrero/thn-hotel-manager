<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotelAvailability;

use App\Shared\Application\Bus\Query\Query;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

final class FindHotelAvailabilityQuery extends Query
{
    private const string HOTEL_ID = 'hotel_id';
    private const string FROM_DATE = 'from_date';
    private const string TO_DATE = 'to_date';

    public static function create(
        string $hotelId,
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate
    ): self {
        return new self([
            self::HOTEL_ID => $hotelId,
            self::FROM_DATE => $fromDate->format('Y-m-d'),
            self::TO_DATE => $toDate->format('Y-m-d'),
        ]);
    }

    public function hotelId(): Uuid
    {
        return Uuid::fromString($this->get(self::HOTEL_ID));
    }

    public function fromDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->get(self::FROM_DATE));
    }

    public function toDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->get(self::TO_DATE));
    }

    protected static function stringMessageName(): string
    {
        return 'hotel_management.query.hotel.find_availability';
    }

    protected function version(): string
    {
        return '1.0';
    }
}
