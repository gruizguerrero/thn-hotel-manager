<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\ValueObject\IntegerValueObject;

final class NumberOfRooms extends IntegerValueObject
{
    public function __construct(int $numberOfRooms)
    {
        $this->guardAgainstNegativeValue($numberOfRooms);
        parent::__construct($numberOfRooms);
    }

    private function guardAgainstNegativeValue(int $numberOfRooms): void
    {
        Assert::greaterOrEqualThan($numberOfRooms, 0);
    }
}
