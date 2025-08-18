<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Read\Repository;

use App\Context\Hotel\Domain\Read\Entity\AvailableRooms;

interface AvailabilityCalendarViewRepository
{
    public function findAvailability(
        string $hotelId,
        \DateTimeImmutable $fromDate,
        \DateTimeImmutable $toDate
    ): AvailableRooms;
}
