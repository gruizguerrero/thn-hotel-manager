<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\DataFixtures\DataLoader\MySQL\HotelFixtures;
use Symfony\Component\HttpKernel\KernelInterface;

final class HotelContext extends AggregateContext
{
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);
    }

    /**
     * @Given /^I have hotels$/
     */
    public function iHaveHotels(): void
    {
        $this->loadFixtures(new HotelFixtures());
    }

    protected function purge(): void
    {
        $this->purgeTables('hotels');
    }
}
