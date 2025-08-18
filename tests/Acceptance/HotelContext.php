<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Context\Availability\Domain\Read\Repository\AvailabilityCalendarViewRepository;
use App\Tests\DataFixtures\DataLoader\MySQL\AvailabilityFixtures;
use App\Tests\DataFixtures\DataLoader\MySQL\HotelFixtures;
use Symfony\Component\HttpKernel\KernelInterface;

final class HotelContext extends AggregateContext
{
    public function __construct(
        KernelInterface $kernel,
        private AvailabilityCalendarViewRepository $availabilityCalendarViewRepository,
    ){
        parent::__construct($kernel);
    }

    /**
     * @Given /^I have hotels$/
     */
    public function iHaveHotels(): void
    {
        $this->loadFixtures(new HotelFixtures());
    }

    /**
     * @Given /^The availability calendar is prepopulated for all hotels and rooms$/
     */
    public function theAvailabilityCalendarIsPrepopulatedForAllHotelsAndRooms(): void
    {
        $this->loadFixtures(new AvailabilityFixtures($this->availabilityCalendarViewRepository));
    }

    protected function purge(): void
    {
        $this->purgeTables('hotels', 'hotel_rooms', 'hotel_rooms_assignment', 'availability_calendar');
    }
}
