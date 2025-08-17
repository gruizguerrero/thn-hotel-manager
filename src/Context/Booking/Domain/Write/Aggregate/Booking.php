<?php

declare(strict_types=1);

namespace App\Context\Booking\Domain\Write\Aggregate;

use App\Context\Booking\Domain\Write\Entity\BookingRoom;
use App\Context\Booking\Domain\Write\Entity\BookingRooms;
use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomIds;
use App\Context\Booking\Domain\Write\Event\BookingCreated;
use App\Context\Booking\Domain\Write\Event\RoomBooked;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;
use DateTimeImmutable;

class Booking extends AggregateRoot
{
    private Uuid $hotelId;
    private Uuid $userId;
    private DateTimeImmutable $checkInDate;
    private DateTimeImmutable $checkOutDate;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    /** @var BookingRooms $rooms */
    private $rooms;

    public static function bookRooms(
        Uuid $bookingId,
        Uuid $hotelId,
        Uuid $userId,
        DateTimeImmutable $checkInDate,
        DateTimeImmutable $checkOutDate,
        RoomIds $roomIds
    ): self {
        $booking = new self($bookingId);
        $booking->hotelId = $hotelId;
        $booking->userId = $userId;
        $booking->checkInDate = $checkInDate;
        $booking->checkOutDate = $checkOutDate;
        $booking->createdAt = new DateTimeImmutable();
        $booking->updatedAt = null;
        $booking->rooms = BookingRooms::createEmpty();

        foreach ($roomIds as $roomId) {
            $bookingRoom = BookingRoom::create(
                Uuid::generate(),
                $roomId
            );

            $booking->rooms->add($bookingRoom);

            $booking->recordEvent(RoomBooked::create(
                $booking->id(),
                $booking->userId,
                $booking->checkInDate,
                $booking->checkOutDate,
                $booking->hotelId,
                $roomId
            ));
        }

        $booking->recordEvent(BookingCreated::create(
            $booking->id(),
            $booking->userId,
            $booking->checkInDate,
            $booking->checkOutDate,
            $booking->hotelId,
            $roomIds
        ));

        return $booking;
    }
}
