<?php

declare(strict_types=1);

namespace App\Context\Availability\Infrastructure\Read\Persistence\MySQL\Repository;

use App\Context\Availability\Domain\Read\Entity\AvailabilityCalendarView;
use App\Context\Availability\Domain\Read\Repository\AvailabilityCalendarViewRepository;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use Doctrine\DBAL\ParameterType;

final class MySQLAvailabilityCalendarRepository extends ViewRepository implements AvailabilityCalendarViewRepository
{
    public function save(AvailabilityCalendarView $availabilityCalendarView): void
    {
        $sql = <<<SQL
INSERT INTO availability_calendar
  (hotel_id, room_id, day, status, capacity)
VALUES
  (:hotel_id, :room_id, :day, :status, :capacity)
SQL;

        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindValue('hotel_id', $availabilityCalendarView->hotelId());
        $stmt->bindValue('room_id',  $availabilityCalendarView->roomId());
        $stmt->bindValue('day',      $availabilityCalendarView->day()->format('Y-m-d'));
        $stmt->bindValue('status',   $availabilityCalendarView->status());
        $stmt->bindValue('capacity', $availabilityCalendarView->capacity(), ParameterType::INTEGER);

        $stmt->executeStatement();
    }
}
