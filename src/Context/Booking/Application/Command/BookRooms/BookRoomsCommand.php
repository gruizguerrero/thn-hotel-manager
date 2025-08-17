<?php

declare(strict_types=1);

namespace App\Context\Booking\Application\Command\BookRooms;

use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomId;
use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomIds;
use App\Shared\Application\Bus\Command\Command;
use App\Shared\Domain\ValueObject\DateTimeInterface;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

final class BookRoomsCommand extends Command
{
    private const string ID = 'id';
    private const string HOTEL_ID = 'hotel_id';

    private const string USER_ID = 'user_id';

    private const string CHECK_IN_DATE = 'check_in_date';
    private const string CHECK_OUT_DATE = 'check_out_date';
    private const string ROOM_IDS = 'room_ids';

    public static function create(
        string $bookingId,
        string $hotelId,
        string $userId,
        DateTimeImmutable $checkInDate,
        DateTimeImmutable $checkOutDate,
        array $roomIds
    ): self {
        return new self([
            self::ID => $bookingId,
            self::HOTEL_ID => $hotelId,
            self::USER_ID => $userId,
            self::CHECK_IN_DATE => $checkInDate->format(DateTimeInterface::APP_FORMAT),
            self::CHECK_OUT_DATE => $checkOutDate->format(DateTimeInterface::APP_FORMAT),
            self::ROOM_IDS => $roomIds
        ]);
    }

    public function id(): Uuid
    {
        return Uuid::fromString($this->get(self::ID));
    }

    public function hotelId(): Uuid
    {
        return Uuid::fromString($this->get(self::HOTEL_ID));
    }

    public function userId(): Uuid
    {
        return Uuid::fromString($this->get(self::USER_ID));
    }

    public function checkInDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DateTimeInterface::APP_FORMAT, $this->get(self::CHECK_IN_DATE));
    }

    public function checkOutDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(DateTimeInterface::APP_FORMAT, $this->get(self::CHECK_OUT_DATE));
    }

    public function roomIds(): RoomIds
    {
        $roomIds = RoomIds::createEmpty();
        foreach ($this->get(self::ROOM_IDS) as $roomId) {
            $roomIds->add(RoomId::fromString($roomId));
        }

        return $roomIds;
    }

    protected static function stringMessageName(): string
    {
        return "booking_management.command.booking.book_rooms";
    }

    protected function version(): string
    {
        return '1.0';
    }
}
