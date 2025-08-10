<?php

namespace App\Shared\Infrastructure\Bus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\BusNameStamp;

final class AddBusNameToStampMiddleware implements MiddlewareInterface
{
    public function __construct(public string $busName)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (empty($envelope->all(BusNameStamp::class))) {
            $envelope = $envelope->with(
                new BusNameStamp($this->busName)
            );
        }

        return $stack->next()->handle(
            $envelope,
            $stack
        );
    }
}
