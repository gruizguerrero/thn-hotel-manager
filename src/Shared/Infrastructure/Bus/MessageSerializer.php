<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Domain\Message\Factory\MessageFactory;
use App\Shared\Domain\Message\Message;
use App\Shared\Domain\Service\Assert;

final readonly class MessageSerializer
{
    public function __construct(private MessageFactory $messageFactory)
    {
    }

    public function serialize(Message $message): array
    {
        return [
            Message::PAYLOAD => $message->payload(),
            Message::METADATA => $message->metadata(),
        ];
    }

    public function deserialize(array $body): Message
    {
        $this->validateBody($body);
        $this->validateMetadata($body);

        return $this->messageFactory->create(
            $body[Message::METADATA][Message::MESSAGE_NAME],
            $body[Message::PAYLOAD],
            $body[Message::METADATA]
        );
    }

    private function validateMetadata(array $body): void
    {
        Assert::keyExists($body, Message::METADATA);
        Assert::that($body[Message::METADATA])
            ->isArray()
            ->keyExists(Message::MESSAGE_NAME)
            ->keyExists(Message::MESSAGE_VERSION);
    }

    private function validateBody(array $body): void
    {
        Assert::keyExists($body, Message::PAYLOAD);
        Assert::isArray($body[Message::PAYLOAD]);
    }
}