<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Booking\Domain\Write\Entity;

use App\Context\Booking\Domain\Write\Aggregate\Booking;
use App\Context\Booking\Domain\Write\Entity\BookingRoom;
use App\Context\Booking\Domain\Write\Entity\BookingRooms;
use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomId;
use App\Context\Booking\Domain\Write\Event\BookingCreated;
use App\Context\Booking\Domain\Write\Event\RoomBooked;
use App\Context\Booking\Domain\Write\Exception\BookingRoomsCannotBeEmptyException;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class BookingRoomTest extends TestCase
{
    public function test_it_creates_booking_room_room_successfully(): void
    {
        $uuid = Uuid::generate();
        $roomId = RoomId::generate();

        $booking = BookingRoom::create($uuid, $roomId);

        $this->assertSame($uuid, $booking->id());
        $this->assertSame($roomId, $booking->roomId());
    }
}
