<?php

namespace App\Context\Booking\Domain\Repository;

use App\Context\Booking\Domain\Write\Aggregate\Booking;

interface BookingRepository
{
    public function save(Booking $booking): void;
}
