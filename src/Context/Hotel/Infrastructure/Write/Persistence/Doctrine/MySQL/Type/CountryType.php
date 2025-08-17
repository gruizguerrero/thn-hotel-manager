<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CountryType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Country
    {
        if (is_null($value)) {
            return null;
        }

        return Country::fromString($value);
    }

    public function getName(): string
    {
        return 'country';
    }
}
