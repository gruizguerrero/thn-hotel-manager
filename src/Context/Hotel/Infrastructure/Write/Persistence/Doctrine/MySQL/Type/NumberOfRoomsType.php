<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\NumberOfRooms;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class NumberOfRoomsType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?NumberOfRooms
    {
        if (null === $value) {
            return null;
        }

        return new NumberOfRooms((int) $value);
    }

    public function getName(): string
    {
        return 'number_of_rooms';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }
}
