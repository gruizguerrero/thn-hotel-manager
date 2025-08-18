<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Context\Booking\Domain\Write\Event\BookingCreated;
use App\Shared\Application\Bus\Event\EventBusInterface;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\HttpKernel\KernelInterface;

final class MetricContext extends AggregateContext
{
    public function __construct(
        KernelInterface $kernel,
        private EventBusInterface $eventBus,
    )
    {
        parent::__construct($kernel);
    }

    /**
     * @When /^I publish booking created event with:$/
     */
    public function iPublishBookingCreatedEventWith(PyStringNode $rawEvent): void
    {
        $events = json_decode($rawEvent->getRaw(), true);

        foreach ($events as $event) {
            $this->eventBus->publish(
                BookingCreated::fromPayloadAndMetadata(
                    $event['payload'],
                    $event['metadata'],
                )
            );
        }
    }

    protected function purge(): void
    {
        $this->purgeTables('metric_hotel_users', 'metric_hotel_users_detail');
    }
}