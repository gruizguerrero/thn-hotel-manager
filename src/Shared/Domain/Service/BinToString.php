<?php

declare(strict_types=1);

namespace App\Shared\Domain\Service;

use App\Shared\Domain\ValueObject\Uuid;

final class BinToString
{
    public static function transformUuid(string $binary): string
    {
        return Uuid::addUuid4Dashes(sodium_bin2hex($binary));
    }
}
