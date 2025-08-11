<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Write\Event;

use App\Shared\Domain\Service\UuidGenerator;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Event\DomainEvent;
use App\Tests\Unit\Shared\Domain\Write\Aggregate\FakeAggregateRoot;

final class FakeDomainEvent extends DomainEvent
{
    private const string A_FIELD_KEY = 'AN_EVENT_FIELD';

    public static function create(?FakeAggregateRoot $aggregateRoot = null): DomainEvent
    {
        return self::fromPayload(
            [
                self::AGGREGATE_ROOT_ID => $aggregateRoot ? $aggregateRoot->id()->value() : UuidGenerator::generate()->value(),
                self::A_FIELD_KEY => $aggregateRoot ? $aggregateRoot->aField() : 'a_value',
            ]
        );
    }

    public function id(): Uuid
    {
        return Uuid::fromString($this->get(self::AGGREGATE_ROOT_ID));
    }

    protected static function stringMessageName(): string
    {
        return 'bc.type.module.fake_event';
    }

    protected function version(): string
    {
        return '1.0';
    }
}
