<?php

declare(strict_types=1);

namespace App\Context\Metric\Domain\Read\Entity;

use App\Shared\Domain\Read\View;

final readonly class HotelUniqueUsersView implements View, \JsonSerializable
{
    public function __construct(
        private string $hotelId,
        private int $uniqueUsers
    ) {
    }

    public function hotelId(): string
    {
        return $this->hotelId;
    }

    public function uniqueUsers(): int
    {
        return $this->uniqueUsers;
    }

    public function jsonSerialize(): array
    {
        return [
            'hotelId' => $this->hotelId,
            'uniqueUsers' => $this->uniqueUsers,
        ];
    }
}
