<?php

namespace App\Shared\Domain\Write\Event;

use App\Shared\Domain\TypedCollection;

class DomainEventStream extends TypedCollection
{
    protected function type(): string
    {
        return DomainEvent::class;
    }
}
