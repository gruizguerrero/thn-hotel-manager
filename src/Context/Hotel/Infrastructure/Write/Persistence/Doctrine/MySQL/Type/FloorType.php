<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use App\Context\Hotel\Domain\Write\Entity\ValueObject\Floor;
use App\Shared\Domain\ValueObject\IntegerValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\Type;

final class FloorType extends Type
{
    public const NAME = 'floor';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (null === $value) {
            return null;
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?IntegerValueObject
    {
        if (null === $value) {
            return null;
        }

        return new Floor((int) $value);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }
}
