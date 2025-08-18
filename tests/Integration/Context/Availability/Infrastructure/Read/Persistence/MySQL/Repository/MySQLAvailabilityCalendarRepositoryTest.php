<?php

declare(strict_types=1);

namespace App\Tests\Integration\Context\Availability\Infrastructure\Read\Persistence\MySQL\Repository;

use App\Context\Availability\Domain\Read\Entity\AvailabilityCalendarView;
use App\Context\Availability\Domain\Read\Repository\AvailabilityCalendarViewRepository;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use App\Tests\Integration\ViewRepositoryTestCase;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

final class MySQLAvailabilityCalendarRepositoryTest extends ViewRepositoryTestCase
{
    private const string HOTEL_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const string ROOM_ID = '7c4ef9ec-32a7-4f76-b68d-cf35d5f46c1d';
    private const string STATUS_AVAILABLE = 'AVAILABLE';
    private const int CAPACITY = 2;

    public function test_it_saves_availability_calendar_view(): void
    {
        $day = new DateTimeImmutable('2025-09-15');
        $availabilityCalendarView = new AvailabilityCalendarView(
            self::HOTEL_ID,
            self::ROOM_ID,
            $day,
            self::STATUS_AVAILABLE,
            self::CAPACITY
        );

        $this->repository()->save($availabilityCalendarView);

        $result = $this->findCalendarEntryInDatabase(
            self::HOTEL_ID,
            self::ROOM_ID,
            $day->format('Y-m-d')
        );

        $this->assertNotEmpty($result, 'Availability calendar entry was not saved in database');
        $this->assertEquals(self::HOTEL_ID, $result['hotel_id']);
        $this->assertEquals(self::ROOM_ID, $result['room_id']);
        $this->assertEquals($day->format('Y-m-d'), $result['day']);
        $this->assertEquals(self::STATUS_AVAILABLE, $result['status']);
        $this->assertEquals(self::CAPACITY, $result['capacity']);
    }

    protected function repository(): ViewRepository
    {
        return $this->getContainer()->get(AvailabilityCalendarViewRepository::class);
    }

    private function findCalendarEntryInDatabase(string $hotelId, string $roomId, string $day): array
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get(Connection::class);

        $sql = "SELECT * FROM availability_calendar 
                WHERE hotel_id = :hotel_id 
                AND room_id = :room_id 
                AND day = :day";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue('hotel_id', $hotelId);
        $stmt->bindValue('room_id', $roomId);
        $stmt->bindValue('day', $day);

        $result = $stmt->executeQuery()->fetchAssociative();

        return $result ?: [];
    }

    protected function purge(): void
    {
        $this->purgeTables('availability_calendar');
    }
}
