<?php

declare(strict_types=1);

namespace App\Tests\Integration\Context\Booking\Infrastructure\Write\Persistence\Doctrine\Repository;

use App\Context\Booking\Domain\Write\Aggregate\Booking;
use App\Context\Booking\Domain\Write\Entity\BookingRoom;
use App\Context\Booking\Domain\Write\Entity\BookingRooms;
use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomId;
use App\Context\Booking\Domain\Write\Repository\BookingRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;
use App\Tests\Integration\RepositoryTestCase;

final class DoctrineBookingRepositoryTest extends RepositoryTestCase
{
    public function test_it_books_rooms(): void
    {
        $this->expectNotToPerformAssertions();

        $id = Uuid::generate();
        $hotelId = Uuid::generate();
        $userId = Uuid::generate();
        $checkInDate = new \DateTimeImmutable();
        $checkOutDate = (new \DateTimeImmutable())->modify('+2 days');
        $bookingRooms = BookingRooms::create(
            [
                BookingRoom::create(Uuid::generate(), RoomId::generate()),
                BookingRoom::create(Uuid::generate(), RoomId::generate()),
            ]
        );

        $booking = Booking::bookRooms(
            $id,
            $hotelId,
            $userId,
            $checkInDate,
            $checkOutDate,
            $bookingRooms
        );

        $this->repository()->save($booking);
        $this->em()->flush();
        $this->em()->clear();
    }

    protected function purge(): void
    {
        $this->purgeTables('bookings', 'booking_rooms', 'booking_rooms_assignment');
    }

    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . BookingRepository::class);
    }
}