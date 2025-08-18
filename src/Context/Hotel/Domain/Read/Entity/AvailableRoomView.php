<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Read\Entity;

use App\Shared\Domain\Read\View;

final readonly class AvailableRoomView implements View, \JsonSerializable
{
    public function __construct(private string $roomId, private int $capacity)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'roomId' => $this->roomId,
            'capacity' => $this->capacity,
        ];
    }
}
