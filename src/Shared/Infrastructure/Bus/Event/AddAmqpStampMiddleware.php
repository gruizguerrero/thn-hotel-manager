<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Message\Message;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class AddAmqpStampMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /** @var Message $message */
        $message = $envelope->getMessage();

        $messageName = (string) $message::messageName();

        return $stack->next()->handle(
            $envelope->with(
                new AmqpStamp(
                    $messageName,
                )
            ),
            $stack
        );
    }
}