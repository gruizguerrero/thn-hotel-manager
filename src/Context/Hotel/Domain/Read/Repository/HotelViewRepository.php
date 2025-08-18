<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Read\Repository;

use App\Context\Hotel\Domain\Read\Entity\HotelView;

interface HotelViewRepository
{
    public function find(string $hotelId): HotelView;
}
