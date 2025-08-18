<?php

declare(strict_types=1);

namespace App\Context\Metric\Infrastructure\Read\Persistence\MySQL;

use App\Context\Metric\Domain\Read\Entity\HotelUniqueUsersView;
use App\Context\Metric\Domain\Read\Repository\HotelUniqueUsersViewRepository;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use Doctrine\DBAL\Exception;

final class MySQLHotelUniqueUsersViewRepository extends ViewRepository implements HotelUniqueUsersViewRepository
{
    public function save(HotelUniqueUsersView $view): void
    {
        $query = <<<SQL
INSERT INTO metric_hotel_users (hotel_id, unique_users)
VALUES (:hotel_id, :unique_users)
ON DUPLICATE KEY UPDATE unique_users = :unique_users
SQL;

        try {
            $statement = $this->entityManager->getConnection()->prepare($query);
            $statement->bindValue('hotel_id', $view->hotelId());
            $statement->bindValue('unique_users', $view->uniqueUsers());
            $statement->executeStatement();
        } catch (Exception $exception) {
            throw new \RuntimeException('Error saving HotelUniqueUsersView: ' . $exception->getMessage(), 0, $exception);
        }
    }

    public function findByHotelId(string $hotelId): ?HotelUniqueUsersView
    {
        $query = <<<SQL
SELECT hotel_id, unique_users
FROM metric_hotel_users
WHERE hotel_id = :hotel_id
SQL;

        try {
            $statement = $this->entityManager->getConnection()->prepare($query);
            $statement->bindValue('hotel_id', $hotelId);
            $result = $statement->executeQuery();

            $data = $result->fetchAssociative();

            if (!$data) {
                return null;
            }

            return new HotelUniqueUsersView(
                $data['hotel_id'],
                (int) $data['unique_users']
            );
        } catch (Exception $exception) {
            throw new \RuntimeException('Error finding HotelUniqueUsersView: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /** Ideally this should be paginated */
    public function findAll(): array
    {
        $query = <<<SQL
SELECT hotel_id, unique_users FROM metric_hotel_users
SQL;
        try {
            $statement = $this->entityManager->getConnection()->prepare($query);
            $result = $statement->executeQuery();

            return $result->fetchAllAssociative();

        } catch (Exception $exception) {
            throw new \RuntimeException('Error finding HotelUniqueUsersView: ' . $exception->getMessage(), 0, $exception);
        }
    }

    public function incrementUniqueUsers(string $hotelId): void
    {
        $query = <<<SQL
INSERT INTO metric_hotel_users (hotel_id, unique_users)
VALUES (:hotel_id, 1)
ON DUPLICATE KEY UPDATE unique_users = unique_users + 1
SQL;

        try {
            $statement = $this->entityManager->getConnection()->prepare($query);
            $statement->bindValue('hotel_id', $hotelId);
            $statement->executeStatement();
        } catch (Exception $exception) {
            throw new \RuntimeException('Error incrementing unique users: ' . $exception->getMessage(), 0, $exception);
        }
    }
}
