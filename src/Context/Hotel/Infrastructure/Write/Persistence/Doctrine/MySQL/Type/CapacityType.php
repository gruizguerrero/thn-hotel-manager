<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use App\Shared\Domain\ValueObject\IntegerValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class CapacityType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        return (int) $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?IntegerValueObject
    {
        if ($value === null) {
            return null;
        }

        return new IntegerValueObject((int) $value);
    }

    public function getName(): string
    {
        return 'capacity';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }
}
