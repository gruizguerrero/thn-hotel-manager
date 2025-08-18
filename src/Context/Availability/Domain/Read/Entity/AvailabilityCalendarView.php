<?php

declare(strict_types=1);

namespace App\Context\Availability\Domain\Read\Entity;

use App\Shared\Domain\Read\View;
use DateTimeImmutable;

final readonly class AvailabilityCalendarView implements View
{
    public function __construct(
        private string $hotelId,
        private string $roomId,
        private DateTimeImmutable $day,
        private string $status,
        private int $capacity
    ) {
    }

    public function hotelId(): string
    {
        return $this->hotelId;
    }

    public function roomId(): string
    {
        return $this->roomId;
    }

    public function day(): DateTimeImmutable
    {
        return $this->day;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }
}
