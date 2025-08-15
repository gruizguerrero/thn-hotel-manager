<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\ValueObject\StringValueObject;

final class Country extends StringValueObject
{
    public function __construct(string $countryCode)
    {
        $this->guardCountryCodeHasISOFormat($countryCode);
        parent::__construct($countryCode);
    }

    private function guardCountryCodeHasISOFormat(string $countryCode): void
    {
        Assert::regex($countryCode, '/^[A-Z]{2}$/', "Invalid country code: $countryCode");
    }
}
