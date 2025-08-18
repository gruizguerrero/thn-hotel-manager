<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\DataFixtures\DataLoader\MySQL\BookingFixtures;
use Symfony\Component\HttpKernel\KernelInterface;

final class BookingContext extends AggregateContext
{
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);
    }

    /**
     * @Given /^I have bookings/
     */
    public function iHaveBookings(): void
    {
        $this->loadFixtures(new BookingFixtures());
    }

    protected function purge(): void
    {
        $this->purgeTables('bookings', 'booking_rooms', 'booking_rooms_assignment');
    }
}
