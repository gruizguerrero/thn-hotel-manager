<?php

namespace App\Shared\Domain\Write\Exception;

use App\Shared\Domain\Exception\DomainException;
use App\Shared\Domain\ValueObject\Uuid;

class EntityNotFoundException extends DomainException
{
    public static function forId(Uuid $id): \Throwable
    {
        return new static(sprintf('Entity of id %s of type %s was not found', $id->value(), get_class($id)));
    }
}