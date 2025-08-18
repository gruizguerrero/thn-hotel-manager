<?php

declare(strict_types=1);

namespace App\Context\Metric\Domain\Read\Entity;

use App\Shared\Domain\Read\View;

final readonly class HotelUserDetailView implements View
{
    public function __construct(
        private string $hotelId,
        private string $userId
    ) {
    }

    public function hotelId(): string
    {
        return $this->hotelId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
