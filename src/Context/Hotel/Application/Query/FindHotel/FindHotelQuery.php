<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Shared\Application\Bus\Query\Query;
use App\Shared\Domain\ValueObject\Uuid;

final class FindHotelQuery extends Query
{
    private const string HOTEL_ID = 'hotel_id';

    public static function fromHotelId(string $hotelId): self
    {
        return new self([
            self::HOTEL_ID => $hotelId
        ]);
    }

    public function hotelId(): Uuid
    {
        return Uuid::fromString($this->get(self::HOTEL_ID));
    }

    protected static function stringMessageName(): string
    {
        return 'hotel_management.query.hotel.find';
    }

    protected function version(): string
    {
        return '1.0';
    }
}
