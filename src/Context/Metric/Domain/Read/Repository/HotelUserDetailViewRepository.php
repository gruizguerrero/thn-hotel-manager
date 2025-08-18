<?php

declare(strict_types=1);

namespace App\Context\Metric\Domain\Read\Repository;

use App\Context\Metric\Domain\Read\Entity\HotelUserDetailView;

interface HotelUserDetailViewRepository
{
    public function save(HotelUserDetailView $view): void;

    public function findByHotelIdAndUserId(string $hotelId, string $userId): ?HotelUserDetailView;
}
