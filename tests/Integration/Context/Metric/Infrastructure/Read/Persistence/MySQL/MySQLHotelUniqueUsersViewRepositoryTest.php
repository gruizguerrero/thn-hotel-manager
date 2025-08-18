<?php

declare(strict_types=1);

namespace App\Tests\Integration\Context\Metric\Infrastructure\Read\Persistence\MySQL;

use App\Context\Metric\Domain\Read\Entity\HotelUniqueUsersView;
use App\Context\Metric\Domain\Read\Repository\HotelUniqueUsersViewRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use App\Tests\Integration\ViewRepositoryTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class MySQLHotelUniqueUsersViewRepositoryTest extends ViewRepositoryTestCase
{
    private const string HOTEL_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const int UNIQUE_USERS = 5;

    public function test_it_saves_hotel_unique_users_view(): void
    {
        $hotelUniqueUsersView = new HotelUniqueUsersView(
            self::HOTEL_ID,
            self::UNIQUE_USERS
        );

        $this->repository()->save($hotelUniqueUsersView);

        $result = $this->findHotelUniqueUsersInDatabase(self::HOTEL_ID);

        $this->assertNotEmpty($result);
        $this->assertEquals(self::HOTEL_ID, $result['hotel_id']);
        $this->assertEquals(self::UNIQUE_USERS, (int) $result['unique_users']);
    }

    public function test_it_updates_existing_hotel_unique_users_view(): void
    {
        $initialUniqueUsers = 3;
        $updatedUniqueUsers = 7;

        $initialView = new HotelUniqueUsersView(
            self::HOTEL_ID,
            $initialUniqueUsers
        );

        $this->repository()->save($initialView);

        $initialResult = $this->findHotelUniqueUsersInDatabase(self::HOTEL_ID);
        $this->assertEquals($initialUniqueUsers, (int) $initialResult['unique_users']);

        $updatedView = new HotelUniqueUsersView(
            self::HOTEL_ID,
            $updatedUniqueUsers
        );
        $this->repository()->save($updatedView);

        $updatedResult = $this->findHotelUniqueUsersInDatabase(self::HOTEL_ID);
        $this->assertEquals($updatedUniqueUsers, (int) $updatedResult['unique_users']);
    }

    public function test_it_finds_hotel_unique_users_by_hotel_id(): void
    {
        $hotelUniqueUsersView = new HotelUniqueUsersView(
            self::HOTEL_ID,
            self::UNIQUE_USERS
        );
        $this->repository()->save($hotelUniqueUsersView);
        $result = $this->repository()->findByHotelId(self::HOTEL_ID);

        $this->assertNotNull($result);
        $this->assertEquals(self::HOTEL_ID, $result->hotelId());
        $this->assertEquals(self::UNIQUE_USERS, $result->uniqueUsers());
    }

    public function test_it_returns_null_when_hotel_not_found(): void
    {
        $result = $this->repository()->findByHotelId(Uuid::generate()->value());

        $this->assertNull($result);
    }

    public function test_it_increments_unique_users(): void
    {
        $initialUniqueUsers = 10;
        $hotelUniqueUsersView = new HotelUniqueUsersView(
            self::HOTEL_ID,
            $initialUniqueUsers
        );
        $this->repository()->save($hotelUniqueUsersView);

        $this->repository()->incrementUniqueUsers(self::HOTEL_ID);

        $result = $this->findHotelUniqueUsersInDatabase(self::HOTEL_ID);
        $this->assertEquals($initialUniqueUsers + 1, (int) $result['unique_users']);
    }

    protected function repository(): ViewRepository
    {
        return $this->getContainer()->get(HotelUniqueUsersViewRepository::class);
    }

    private function findHotelUniqueUsersInDatabase(string $hotelId): array
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $sql = "SELECT * FROM metric_hotel_users WHERE hotel_id = :hotel_id";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue('hotel_id', $hotelId);

        $result = $stmt->executeQuery()->fetchAssociative();

        return $result ?: [];
    }

    protected function purge(): void
    {
        $this->purgeTables('metric_hotel_users');
    }
}
