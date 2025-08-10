<?php

namespace App\Context\Campaign\Domain\Write\Aggregate\ValueObject;

use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\ValueObject\StringValueObject;

class Name extends StringValueObject
{
    private const int MINIMUM_LENGTH = 1;

    public function __construct(string $name)
    {
        $this->guardValidName($name);
        parent::__construct($name);
    }

    private function guardValidName(string $name): void
    {
        Assert::greaterOrEqualThan(mb_strlen(trim($name)), self::MINIMUM_LENGTH, 'Not a valid name');
    }
}
