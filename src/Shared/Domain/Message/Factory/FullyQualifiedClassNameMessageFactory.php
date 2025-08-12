<?php

namespace App\Shared\Domain\Message\Factory;

use App\Shared\Domain\Message\Message;
use App\Shared\Domain\Service\Assert;

final class FullyQualifiedClassNameMessageFactory implements MessageFactory
{
    private array $keyToClassNameMap;

    public function create(string $messageName, array $payload, array $metadata): Message
    {
        /** @var Message $message */
        $message = $this->keyToClassNameMap[$messageName];

        return $message::fromPayloadAndMetadata($payload, $metadata);
    }

    public function addMessagesToMap(array $messageClassNames): void
    {
        Assert::allString($messageClassNames);
        Assert::allSubclassOf($messageClassNames, Message::class);

        /** @var Message $messageClassName */
        foreach ($messageClassNames as $messageClassName) {
            $this->keyToClassNameMap[(string) $messageClassName::messageName()] = $messageClassName;
        }
    }
}
