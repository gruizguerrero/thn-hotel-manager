<?php

declare(strict_types=1);

namespace App\Context\Booking\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use App\Context\Booking\Domain\Repository\BookingRepository;
use App\Context\Booking\Domain\Write\Aggregate\Booking;
use App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

class DoctrineBookingRepository extends AggregateRepository implements BookingRepository
{
    public function save(Booking $booking): void
    {
        $this->saveAggregate($booking);
    }

    protected function entityClassName(): string
    {
        return Booking::class;
    }
}
