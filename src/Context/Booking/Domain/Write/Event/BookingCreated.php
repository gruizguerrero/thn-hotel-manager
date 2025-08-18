<?php

declare(strict_types=1);

namespace App\Context\Booking\Domain\Write\Event;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Event\DomainEvent;
use DateTimeImmutable;

class BookingCreated extends DomainEvent
{
    private const string USER_ID = 'user_id';
    private const string CHECK_IN_DATE = 'check_in_date';
    private const string CHECK_OUT_DATE = 'check_out_date';
    private const string HOTEL_ID = 'hotel_id';

    public static function create(
        Uuid $bookingId,
        Uuid $userId,
        DateTimeImmutable $checkInDate,
        DateTimeImmutable $checkOutDate,
        Uuid $hotelId,
    ): self {
        return new self([
            self::AGGREGATE_ROOT_ID => $bookingId->value(),
            self::USER_ID => $userId->value(),
            self::CHECK_IN_DATE => $checkInDate->format(DATE_ATOM),
            self::CHECK_OUT_DATE => $checkOutDate->format(DATE_ATOM),
            self::HOTEL_ID => $hotelId->value(),
        ]);
    }

    public function userId(): Uuid
    {
        return Uuid::fromString($this->get(self::USER_ID));
    }

    public function checkInDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->get(self::CHECK_IN_DATE));
    }

    public function checkOutDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->get(self::CHECK_OUT_DATE));
    }

    public function hotelId(): Uuid
    {
        return Uuid::fromString($this->get(self::HOTEL_ID));
    }

    protected static function stringMessageName(): string
    {
        return 'booking_management.domain_event.booking.created';
    }

    protected function version(): string
    {
        return '1.0';
    }
}
