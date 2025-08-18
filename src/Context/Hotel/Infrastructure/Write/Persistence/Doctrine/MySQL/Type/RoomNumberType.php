<?php

declare(strict_types=1);

namespace App\Context\Hotel\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use App\Context\Hotel\Domain\Write\Entity\ValueObject\RoomNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class RoomNumberType extends StringType
{
    public const NAME = 'room_number';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?RoomNumber
    {
        if (null === $value) {
            return null;
        }

        return RoomNumber::fromString($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
