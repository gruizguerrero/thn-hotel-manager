<?php

declare(strict_types=1);

namespace App\Context\Metric\Infrastructure\Read\Persistence\MySQL;

use App\Context\Metric\Domain\Read\Entity\HotelUserDetailView;
use App\Context\Metric\Domain\Read\Repository\HotelUserDetailViewRepository;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use Doctrine\DBAL\Exception;

final class MySQLHotelUserDetailViewRepository extends ViewRepository implements HotelUserDetailViewRepository
{
    public function save(HotelUserDetailView $view): void
    {
        $query = <<<SQL
INSERT IGNORE INTO metric_hotel_users_detail (hotel_id, user_id)
VALUES (:hotel_id, :user_id)
SQL;

        try {
            $statement = $this->entityManager->getConnection()->prepare($query);
            $statement->bindValue('hotel_id', $view->hotelId());
            $statement->bindValue('user_id', $view->userId());
            $statement->executeStatement();
        } catch (Exception $exception) {
            throw new \RuntimeException('Error saving HotelUserDetailView: ' . $exception->getMessage(), 0, $exception);
        }
    }

    public function findByHotelIdAndUserId(string $hotelId, string $userId): ?HotelUserDetailView
    {
        $query = <<<SQL
SELECT hotel_id, user_id
FROM metric_hotel_users_detail
WHERE hotel_id = :hotel_id AND user_id = :user_id
SQL;

        try {
            $statement = $this->entityManager->getConnection()->prepare($query);
            $statement->bindValue('hotel_id', $hotelId);
            $statement->bindValue('user_id', $userId);
            $result = $statement->executeQuery();

            $data = $result->fetchAssociative();

            if (!$data) {
                return null;
            }

            return new HotelUserDetailView(
                $data['hotel_id'],
                $data['user_id']
            );
        } catch (Exception $exception) {
            throw new \RuntimeException('Error finding HotelUserDetailView: ' . $exception->getMessage(), 0, $exception);
        }
    }
}
