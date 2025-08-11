<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain\Write\Event;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;
use App\Shared\Domain\Write\Event\DomainEvent;
use App\Tests\Unit\Shared\Domain\Write\Aggregate\FakeAggregateRoot;
use Assert\AssertionFailedException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class DomainEventTest extends TestCase
{
    public function test_it_throws_exception_with_no_identifier(): void
    {
        $this->expectException(AssertionFailedException::class);
        FakeDomainEvent::fromPayload([]);
    }

    public function test_it_throws_exception_with_wrong_identifier(): void
    {
        $this->expectException(AssertionFailedException::class);
        FakeDomainEvent::fromPayload([DomainEvent::AGGREGATE_ROOT_ID => 'test']);
    }

    public function test_it_generates_an_event_with_right_identifier(): void
    {
        $aggregateRoot = $this->givenAnAggregateRoot();
        $event = $this->givenAnEventFrom($aggregateRoot);
        $this->thenTheRootIdWorksAsExpected($aggregateRoot->id()->value(), $event->id()->value());
    }

    public function test_it_should_return_initial_aggregate_version_when_not_included_in_metadata(): void
    {
        $aggregateRoot = $this->givenAnAggregateRoot();
        $event = $this->givenAnEventFrom($aggregateRoot);
        $this->thenInitialAggregateVersionIsReturned($event->aggregateVersion());
    }

    public function test_it_returns_given_aggregate_version_when_included_in_metadata(): void
    {
        $expectedAggregateVersion = 3;
        $aggregateRoot = $this->givenAnAggregateRoot();
        $event = $this->givenAnEventFrom($aggregateRoot);
        $this->thenGivenAggregateVersionIsReturned($expectedAggregateVersion, $event->withAggregateVersion($expectedAggregateVersion));
    }

    public function test_it_returns_the_same_occurred_on_as_the_passed_when_re_creating_the_event_from_payload_and_metadata(): void
    {
        $expectedOccurredOn = new DateTimeImmutable();

        $event = $this->givenAnEventWithAPastOccurredOn($expectedOccurredOn);
        $this->thenTheOccurredOnDateIsKept($expectedOccurredOn, $event->occurredOn());
    }

    public function givenAnAggregateRoot(): FakeAggregateRoot
    {
        return FakeAggregateRoot::random();
    }

    private function givenAnEventFrom(AggregateRoot $aggregateRoot): DomainEvent
    {
        return FakeDomainEvent::create($aggregateRoot);
    }

    private function givenAnEventWithAPastOccurredOn(DateTimeImmutable $date): DomainEvent
    {
        $payload = [DomainEvent::AGGREGATE_ROOT_ID => Uuid::generate()->value()];

        $metadata = ['occurred_on' => $date->format(DomainEvent::DATE_TIME_FORMAT)];

        return FakeDomainEvent::fromPayloadAndMetadata($payload, $metadata);
    }

    private function thenTheOccurredOnDateIsKept(
        DateTimeImmutable $expectedOccurredOn,
        DateTimeImmutable $actualOccurredOn
    ): void {
        $this->assertEquals(
            $expectedOccurredOn->format(DomainEvent::DATE_TIME_FORMAT),
            $actualOccurredOn->format(DomainEvent::DATE_TIME_FORMAT)
        );
    }

    private function thenTheRootIdWorksAsExpected(
        string $expectedId,
        string $actualId
    ): void {
        $this->assertSame($expectedId, $actualId);
    }

    private function thenInitialAggregateVersionIsReturned(
        int $actualAggregateVersion
    ): void {
        $this->assertSame(1, $actualAggregateVersion);
    }

    private function thenGivenAggregateVersionIsReturned(
        int $expectedAggregateVersion,
        DomainEvent $event,
    ): void {
        $this->assertSame($expectedAggregateVersion, $event->aggregateVersion());
    }
}