<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\NumberOfRooms;
use PHPUnit\Framework\TestCase;

final class NumberOfRoomsTest extends TestCase
{
    public function test_increment(): void
    {
        $numberOfRooms = new NumberOfRooms(5);
        $numberOfRooms = $numberOfRooms->increment();

        $this->assertEquals(6, $numberOfRooms->value());
    }
}