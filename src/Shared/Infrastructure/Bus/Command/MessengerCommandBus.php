<?php

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Application\Bus\Command\Command;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->messageBus->dispatch(Envelope::wrap($command));
        } catch (HandlerFailedException $exception) {
            throw current($exception->getWrappedExceptions());
        }
    }
}
