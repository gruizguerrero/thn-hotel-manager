<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Type;

use App\Shared\Domain\Service\StringToBin;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UuidType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if (is_null($value)) {
            return null;
        }

        $uuid = \Ramsey\Uuid\Uuid::fromBytes($value);

        $specificUuidType = $this->specificUuidType();

        return $specificUuidType::fromString($uuid->toString());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return !is_null($value) ? StringToBin::transformUuid($value->value()): null;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBinaryTypeDeclarationSQL(
            [
                'length' => '16',
                'fixed' => true,
            ],
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getBindingType(): ParameterType
    {
        return ParameterType::BINARY;
    }

    protected function specificUuidType(): string
    {
        return Uuid::class;
    }

    public function getName(): string
    {
        return 'uuid';
    }
}