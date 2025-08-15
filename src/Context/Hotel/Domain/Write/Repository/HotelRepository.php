<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Repository;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;
use App\Shared\Domain\ValueObject\Uuid;

interface HotelRepository
{
    public function save(Hotel $hotel): void;
    public function find(Uuid $id): Hotel;
}
