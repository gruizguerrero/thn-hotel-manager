<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Helpers;

final class FakeCollectionElement
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }
}
