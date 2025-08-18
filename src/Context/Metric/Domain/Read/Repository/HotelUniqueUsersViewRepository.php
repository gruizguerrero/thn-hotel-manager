<?php

declare(strict_types=1);

namespace App\Context\Metric\Domain\Read\Repository;

use App\Context\Metric\Domain\Read\Entity\HotelUniqueUsersView;

interface HotelUniqueUsersViewRepository
{
    public function save(HotelUniqueUsersView $view): void;

    public function findByHotelId(string $hotelId): ?HotelUniqueUsersView;

    public function incrementUniqueUsers(string $hotelId): void;

    public function findAll(): array;
}
