<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Booking\Domain\Write\Aggregate;

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

final class BookingTest extends TestCase
{
    private const string BOOKING_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const string HOTEL_ID = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
    private const string USER_ID = '7c4ef9ec-32a7-4f76-b68d-cf35d5f46c1d';
    private const string ROOM_ID = 'e8077d50-b2bb-4c2f-a7f2-7a96a3c6c9f0';

    public function test_it_throws_exception_when_booking_rooms_empty(): void
    {
        $bookingId = Uuid::fromString(self::BOOKING_ID);
        $hotelId = Uuid::fromString(self::HOTEL_ID);
        $userId = Uuid::fromString(self::USER_ID);
        $checkInDate = new DateTimeImmutable('2025-09-01');
        $checkOutDate = new DateTimeImmutable('2025-09-05');
        $emptyRooms = BookingRooms::createEmpty();

        $this->expectException(BookingRoomsCannotBeEmptyException::class);
        $this->expectExceptionMessage('Booking must have at least one room');

        Booking::bookRooms(
            $bookingId,
            $hotelId,
            $userId,
            $checkInDate,
            $checkOutDate,
            $emptyRooms
        );
    }

    public function test_it_creates_booking_with_rooms_successfully(): void
    {
        $bookingId = Uuid::fromString(self::BOOKING_ID);
        $hotelId = Uuid::fromString(self::HOTEL_ID);
        $userId = Uuid::fromString(self::USER_ID);
        $checkInDate = new DateTimeImmutable('2025-09-01');
        $checkOutDate = new DateTimeImmutable('2025-09-05');

        $bookingRoom = BookingRoom::create(
            Uuid::generate(),
            RoomId::fromString(self::ROOM_ID)
        );

        $bookingRooms = BookingRooms::createEmpty();
        $bookingRooms->add($bookingRoom);

        $booking = Booking::bookRooms(
            $bookingId,
            $hotelId,
            $userId,
            $checkInDate,
            $checkOutDate,
            $bookingRooms
        );

        $this->assertSame($bookingId, $booking->id());
        $this->assertSame($hotelId, $booking->hotelId());
        $this->assertSame($userId, $booking->userId());
        $this->assertEquals($checkInDate, $booking->checkInDate());
        $this->assertEquals($checkOutDate, $booking->checkOutDate());
        $this->assertCount(1, $booking->rooms());

        $events = $booking->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(RoomBooked::class, $events->first());
        $this->assertInstanceOf(BookingCreated::class, $events->get(1));
    }
}
