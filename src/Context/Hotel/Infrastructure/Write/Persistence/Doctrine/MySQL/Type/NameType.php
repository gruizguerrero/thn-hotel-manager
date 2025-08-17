<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class NameType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Name
    {
        if (is_null($value)) {
            return null;
        }

        return Name::fromString($value);
    }

    public function getName(): string
    {
        return 'name';
    }
}
