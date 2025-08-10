<?php

namespace App\Shared\Domain\Service;

final class StringToBin
{
    public static function transformUuid(string $string): string
    {
        return sodium_hex2bin(str_replace('-', '', $string));
    }
}
