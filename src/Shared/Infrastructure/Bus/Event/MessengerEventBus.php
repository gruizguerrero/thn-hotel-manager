<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Domain\Write\Event\DomainEvent;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerEventBus implements EventBusInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function publish(DomainEvent $event): void
    {
        $this->messageBus->dispatch($this->buildEnvelope($event));
    }

    private function buildEnvelope(DomainEvent $domainEvent): Envelope
    {
        return new Envelope($domainEvent);
    }
}