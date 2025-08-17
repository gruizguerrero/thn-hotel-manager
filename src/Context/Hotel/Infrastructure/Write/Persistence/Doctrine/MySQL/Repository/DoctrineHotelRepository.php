<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;
use App\Context\Hotel\Domain\Write\Repository\HotelRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineHotelRepository extends AggregateRepository implements HotelRepository
{
    public function save(Hotel $hotel): void
    {
        $this->saveAggregate($hotel);
    }

    public function find(Uuid $id): Hotel
    {
        return $this->doFind($id);
    }

    protected function entityClassName(): string
    {
        return Hotel::class;
    }
}