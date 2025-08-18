<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\Write\Event\DomainEvent;
use ReflectionClass;
use Symfony\Component\HttpKernel\KernelInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class MessageContext extends BaseContext
{
    public function __construct(
        KernelInterface $kernel,
        private AMQPStreamConnection $connection,
    ) {
        parent::__construct($kernel);
    }

    /**
     * @When /^The message is consumed from "([^"]*)" transport for "([^"]*)" bus$/
     */
    public function theMessageIsConsumedFromTransportForBus($transport, $bus): void
    {
        $this->consumeMessageFromTransportForBus($transport, $bus);
    }

    /**
     * @When /^I consume "([^"]*)" messages from "([^"]*)" transport for "([^"]*)" bus$/
     */
    public function iConsumeGivenMessagesFromTransportForBus($totalMessages, $transport, $bus): void
    {
        $this->consumeMessageFromTransportForBus($transport, $bus, $totalMessages);
    }

    /**
     * @When /^I consume "([^"]*)" messages from "([^"]*)" transport for "([^"]*)" bus from "([^"]*)" queue$/
     */
    public function iConsumeGivenMessagesFromTransportForBusFromQueue($totalMessages, $transport, $bus, $queue): void
    {
        $this->consumeMessageFromTransportForBusFromQueue($transport, $bus, $queue, $totalMessages);
    }

    /**
     * @Then I have :totalMessages domain messages of type :messageName dispatched
     */
    public function thenIHaveGivenTheDomainMessagesDispatched(int $totalMessages, string $messageName): void
    {
        $domainMessageDispatched = $this->getDomainMessageDispatched();

        $foundMessages = [];

        foreach ($domainMessageDispatched as $domainMessage) {
            if ($domainMessage['message']->metadata()['name'] === $messageName) {
                $foundMessages[] = $domainMessage;
            }
        }

        Assert::count($foundMessages, $totalMessages);
    }

    private function consumeMessageFromTransportForBus($transport, $bus, $limit = null): void
    {
        $this->iRunACommand(
            'messenger:consume',
            [
                'receivers' => [$transport],
                '--bus' => $bus,
                '--limit' => $limit ? (int) $limit : 1,
                '--time-limit' => 30
            ]
        );
    }

    private function consumeMessageFromTransportForBusFromQueue($transport, $bus, $queue, $limit): void
    {
        $this->iRunACommand(
            'messenger:consume',
            [
                'receivers' => [$transport],
                '--bus' => $bus,
                '--limit' => $limit,
                '--time-limit' => 30,
                '--queues' => [$queue]
            ]
        );
    }

    private function getDomainMessageDispatched(): ?array
    {
        $domainMessageBus = $this->kernel->getContainer()->get('test.App\Shared\Infrastructure\Bus\Event\MessengerEventBus');
        $reflectionClass = new ReflectionClass(get_class($domainMessageBus));
        $reflectionProperty = $reflectionClass->getProperty('messageBus');
        $reflectionProperty->setAccessible(true);
        $messageBus = $reflectionProperty->getValue($domainMessageBus);
        $reflectionProperty->setAccessible(false);

        $dispatchedMessages = $messageBus->getDispatchedMessages() ?? [];

        $domainEvents = [];

        // Filter is needed, it gets all messages types in the messenger
        foreach ($dispatchedMessages as $message) {
            if ($message['message'] instanceof DomainEvent) {
                $domainEvents[] = $message;
            }
        }

        return $domainEvents;
    }

    /**
     * @BeforeScenario @purgeQueues
     */
    public function purgeQueues(): void
    {
        $this->purgeAllQueues();
    }

    private function purgeAllQueues(): void
    {
        $queueNames = [
            'ha_async_domain_event_queue',
        ];

        $channel = $this->connection->channel();

        $this->purgeChannelQueues($queueNames, $channel);
    }

    public function purgeChannelQueues(
        array $queueNames,
        AMQPChannel $channel
    ): void {
        foreach ($queueNames as $queueName) {
            $channel->queue_purge($queueName);
        }

        $channel->close();
    }
}
