<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

class ViewNotFoundException extends DomainException
{
    public static function forViewFQCNAndId(string $id, string $className): self
    {
        return new self(sprintf('View of class %s with id %s does not exist', $className, $id));
    }
}
