<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\ValueObject\StringValueObject;

class City extends StringValueObject
{
    private const int MINIMUM_LENGTH = 1;

    public function __construct(string $name)
    {
        $this->guardHasValidCharacters($name);
        $this->guardHasValidLength($name);
        parent::__construct($name);
    }

    private function guardHasValidCharacters(string $name): void
    {
        Assert::regex($name, '/^[a-zA-Z\s-]+$/', 'City should contain only letters');
    }

    private function guardHasValidLength(string $name): void
    {
        Assert::greaterOrEqualThan(mb_strlen(trim($name)), self::MINIMUM_LENGTH, 'Not a valid city');
    }
}
