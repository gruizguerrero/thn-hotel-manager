<?php

namespace App\Shared\Domain\Message;

use App\Shared\Domain\ValueObject\MessageName;
use InvalidArgumentException;

abstract class Message
{
    public const string PREFIX = 'thn';
    public const string MESSAGE_NAME = 'name';
    public const string MESSAGE_VERSION = 'version';
    public const string PAYLOAD = 'payload';
    public const string METADATA = 'metadata';

    public function __construct(protected array $payload, protected array $metadata = [])
    {
        $this->setPayload($payload);

        $defaultMetadata = [
            self::MESSAGE_NAME => (string) static::messageName(),
            self::MESSAGE_VERSION => $this->version(),
        ];
        $metadata = array_merge($defaultMetadata, $metadata);

        $this->setMetadata($metadata);
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload);
    }

    public static function fromPayloadAndMetadata(array $payload, array $metadata): static
    {
        return new static($payload, $metadata);
    }

    private function setPayload(array $payload): void
    {
        $this->guardPayloadIsValid($payload);
        $this->payload = $payload;
    }

    private function guardPayloadIsValid(array $payload): void
    {
        foreach ($payload as $key => $value) {
            if (!$this->isPrimitive($value)) {
                throw new InvalidArgumentException(sprintf('Payload "%s" is not a primitive', $key));
            }
        }
    }

    private function isPrimitive($value): bool
    {
        return is_scalar($value) || is_null($value) || is_array($value);
    }

    private function setMetadata(array $metadata): void
    {
        $this->guardPayloadIsValid($metadata);
        $this->metadata = $metadata;
    }

    protected function get(string $key)
    {
        if (!array_key_exists($key, $this->payload)) {
            throw new InvalidArgumentException(
                sprintf('The element with key <%s> does not exist in the payload', $key)
            );
        }

        return $this->payload[$key];
    }

    protected function tryGet(string $key)
    {
        if (!array_key_exists($key, $this->payload)) {
            return null;
        }

        return $this->payload[$key];
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function withAddedMetadata(array $metadata): self
    {
        $clonedMessage = clone $this;
        $newMetadata = array_merge($this->metadata, $metadata);
        $clonedMessage->setMetadata($newMetadata);

        return $clonedMessage;
    }

    public function metadataVersionMatches(): bool
    {
        return $this->metadata[self::MESSAGE_VERSION] === $this->version();
    }

    abstract protected static function stringMessageName(): string;

    public static function messageName(): MessageName
    {
        return new MessageName(
            implode(
                MessageName::SEPARATOR,
                [
                    self::PREFIX,
                    static::stringMessageName(),
                ]
            )
        );
    }

    abstract protected function version(): string;
}