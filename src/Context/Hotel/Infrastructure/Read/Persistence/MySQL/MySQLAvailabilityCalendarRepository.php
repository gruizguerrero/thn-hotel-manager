<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Read\Persistence\MySQL;

use App\Context\Hotel\Domain\Read\Entity\AvailableRoomView;
use App\Context\Hotel\Domain\Read\Entity\AvailableRooms;
use App\Context\Hotel\Domain\Read\Repository\AvailabilityCalendarViewRepository;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use Doctrine\DBAL\ParameterType;

final class MySQLAvailabilityCalendarRepository extends ViewRepository implements AvailabilityCalendarViewRepository
{
    public function findAvailability(
        string $hotelId,
        \DateTimeImmutable $fromDate,
        \DateTimeImmutable $toDate
    ): AvailableRooms {
        $days = (int) $fromDate->diff($toDate)->days;

        $sql = <<<SQL
SELECT
  room_id,
  MAX(capacity) AS capacity,
  COUNT(*)      AS free_days
FROM availability_calendar
WHERE hotel_id = :hotel
  AND day >= :from
  AND day < :to
  AND status = 'AVAILABLE'
GROUP BY room_id
HAVING free_days = :days
ORDER BY room_id
SQL;
        $rows = $this->entityManager->getConnection()->fetchAllAssociative($sql, [
            'hotel' => $hotelId,
            'from'  => $fromDate->format('Y-m-d'),
            'to'    => $toDate->format('Y-m-d'),
            'days'  => $days,
        ],
            [
                'days'  => ParameterType::INTEGER,
            ]);

        if (empty($rows)) {
            return AvailableRooms::createEmpty();
        }

        $availableRooms = array_map(
            static fn (array $r) => new AvailableRoomView($r['room_id'], (int) $r['capacity']),
            $rows
        );

        return AvailableRooms::create($availableRooms);
    }
}
