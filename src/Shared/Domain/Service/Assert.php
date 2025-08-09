<?php

namespace App\Shared\Domain\Service;

use Assert\Assertion;

final class Assert extends Assertion
{
    public static function uuid4(string $uuid): bool
    {
        parent::uuid($uuid);

        $uuid4pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

        return parent::regex($uuid, $uuid4pattern, sprintf('Value %s is not a valid UUID4', $uuid));
    }
}