<?php

namespace App\Shared\Domain\Write\Event;

use App\Shared\Domain\Message\Message;
use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\ValueObject\DateTimeInterface;
use DateTimeImmutable;

abstract class DomainEvent extends Message
{
    public const string DATE_TIME_FORMAT = DateTimeInterface::APP_FORMAT;
    public const string AGGREGATE_ROOT_ID = 'aggregate_root_id';
    public const string AGGREGATE_VERSION = 'aggregate_version';

    private const int INITIAL_VERSION = 1;
    protected const string OCCURRED_ON = 'occurred_on';

    public function __construct(array $payload = [], array $metadata = [], int $aggregateVersion = null)
    {
        $this->guardAggregateRootIdExists($payload);

        $aggregateVersion = $aggregateVersion ?? self::INITIAL_VERSION;
        Assert::greaterOrEqualThan($aggregateVersion, self::INITIAL_VERSION);

        $defaultMetadata = [
          self::AGGREGATE_ROOT_ID => $payload[self::AGGREGATE_ROOT_ID],
          self::AGGREGATE_VERSION => $aggregateVersion,
          self::OCCURRED_ON => (new DateTimeImmutable())->format(self::DATE_TIME_FORMAT),
        ];

        $metadata = array_merge($defaultMetadata, $metadata);
        $metadata[self::AGGREGATE_VERSION] = $aggregateVersion;

        parent::__construct($payload, $metadata);
    }

    private function guardAggregateRootIdExists(array $payload): void
    {
        Assert::keyExists($payload, self::AGGREGATE_ROOT_ID);
        Assert::uuid4($payload[self::AGGREGATE_ROOT_ID]);
    }

    public function occurredOn(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_FORMAT,
            $this->metadata[self::OCCURRED_ON]
        );
    }

    public function withAggregateVersion(int $aggregateVersion): self
    {
        return new static(
            $this->payload,
            $this->metadata,
            $aggregateVersion
        );
    }

    public function aggregateVersion(): int
    {
        return $this->metadata[self::AGGREGATE_VERSION];
    }
}
