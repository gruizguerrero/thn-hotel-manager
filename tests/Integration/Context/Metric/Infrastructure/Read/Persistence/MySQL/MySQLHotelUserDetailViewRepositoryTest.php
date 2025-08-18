<?php

declare(strict_types=1);

namespace App\Tests\Integration\Context\Metric\Infrastructure\Read\Persistence\MySQL;

use App\Context\Metric\Domain\Read\Entity\HotelUserDetailView;
use App\Context\Metric\Domain\Read\Repository\HotelUserDetailViewRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use App\Tests\Integration\ViewRepositoryTestCase;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

final class MySQLHotelUserDetailViewRepositoryTest extends ViewRepositoryTestCase
{
    private const string HOTEL_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const string USER_ID = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';

    public function test_it_saves_hotel_user_detail_view(): void
    {
        $hotelUserDetail = new HotelUserDetailView(
            self::HOTEL_ID,
            self::USER_ID
        );

        $this->repository()->save($hotelUserDetail);

        $result = $this->findHotelUserDetailInDatabase(self::HOTEL_ID, self::USER_ID);

        $this->assertNotEmpty($result);
        $this->assertEquals(self::HOTEL_ID, $result['hotel_id']);
        $this->assertEquals(self::USER_ID, $result['user_id']);
    }

    public function test_it_does_not_duplicate_entries_for_same_hotel_and_user(): void
    {
        $hotelUserDetail = new HotelUserDetailView(
            self::HOTEL_ID,
            self::USER_ID
        );

        $this->repository()->save($hotelUserDetail);
        $this->repository()->save($hotelUserDetail);

        $count = $this->countHotelUserDetailEntries(self::HOTEL_ID, self::USER_ID);
        $this->assertEquals(1, $count, 'Multiple entries were created for the same hotel-user combination');
    }

    public function test_it_finds_hotel_user_detail_by_hotel_id_and_user_id(): void
    {
        $hotelUserDetail = new HotelUserDetailView(
            self::HOTEL_ID,
            self::USER_ID
        );
        $this->repository()->save($hotelUserDetail);

        $result = $this->repository()->findByHotelIdAndUserId(self::HOTEL_ID, self::USER_ID);

        $this->assertNotNull($result);
        $this->assertEquals(self::HOTEL_ID, $result->hotelId());
        $this->assertEquals(self::USER_ID, $result->userId());
    }

    public function test_it_returns_null_when_hotel_user_detail_not_found(): void
    {
        $result = $this->repository()->findByHotelIdAndUserId(self::HOTEL_ID, Uuid::generate()->value());

        $this->assertNull($result);
    }

    protected function repository(): ViewRepository
    {
        return $this->getContainer()->get(HotelUserDetailViewRepository::class);
    }

    private function findHotelUserDetailInDatabase(string $hotelId, string $userId): array
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get(EntityManagerInterface::class);

        $sql = "SELECT * FROM metric_hotel_users_detail 
                WHERE hotel_id = :hotel_id 
                AND user_id = :user_id";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue('hotel_id', $hotelId);
        $stmt->bindValue('user_id', $userId);

        $result = $stmt->executeQuery()->fetchAssociative();

        return $result ?: [];
    }

    private function countHotelUserDetailEntries(string $hotelId, string $userId): int
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get(Connection::class);

        $sql = "SELECT COUNT(*) as total FROM metric_hotel_users_detail 
                WHERE hotel_id = :hotel_id 
                AND user_id = :user_id";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue('hotel_id', $hotelId);
        $stmt->bindValue('user_id', $userId);

        $result = $stmt->executeQuery()->fetchAssociative();

        return (int) ($result['total'] ?? 0);
    }

    protected function purge(): void
    {
        $this->purgeTables('metric_hotel_users_detail');
    }
}
