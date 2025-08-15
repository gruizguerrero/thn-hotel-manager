<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Application\Bus\Query\Query;
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\Application\Bus\Query\Response;
use App\Shared\Domain\Service\Assert;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerQueryBus implements QueryBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function ask(Query $query): ?Response
    {
        try {
            $envelope = $this->messageBus->dispatch(new Envelope($query));

            /** @var HandledStamp|null $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);

            if (!$handledStamp) {
                return null;
            }

            $response = $handledStamp->getResult();

            Assert::isInstanceOf($response, Response::class);

            return $response;
        } catch (HandlerFailedException $exception) {
            throw current($exception->getWrappedExceptions());
        }
    }
}
