<?php

namespace App\Shared\Application\Bus\Event;

use App\Shared\Domain\Write\Event\DomainEvent;

interface EventBusInterface
{
    public function publish(DomainEvent $event): void;
}