<?php

namespace App\Shared\Domain\Write\Aggregate;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Event\DomainEvent;
use App\Shared\Domain\Write\Event\DomainEventStream;

class AggregateRoot extends Entity
{
    protected int $version = 0;

    protected DomainEventStream $eventStream;

    protected function __construct(Uuid $id)
    {
        $this->eventStream = DomainEventStream::createEmpty();
        parent::__construct($id);
    }

    private function eventStream(): DomainEventStream
    {
        return $this->eventStream ?? ($this->eventStream = DomainEventStream::createEmpty());
    }

    protected function recordEvent(DomainEvent $event): void
    {
        // ToDo. Implement versioning
        $this->eventStream->add($event);
    }

    public function pullEvents(): DomainEventStream
    {
        return $this->eventStream->extract();
    }

    public function version(): int
    {
        return $this->version;
    }
}