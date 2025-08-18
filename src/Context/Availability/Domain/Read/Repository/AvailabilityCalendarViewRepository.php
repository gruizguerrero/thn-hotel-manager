<?php

declare(strict_types=1);

namespace App\Context\Availability\Domain\Read\Repository;

use App\Context\Availability\Domain\Read\Entity\AvailabilityCalendarView;

interface AvailabilityCalendarViewRepository
{
    public function save(AvailabilityCalendarView $availabilityCalendarView): void;
}
