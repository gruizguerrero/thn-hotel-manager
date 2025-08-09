<?php

namespace App\Shared\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;

final class UuidGenerator
{
    public static function generate(): Uuid
    {
        return Uuid::fromString(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }
}