<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\DataLoader\MySQL;

use App\Context\Availability\Domain\Read\Entity\AvailabilityCalendarView;
use App\Context\Availability\Domain\Read\Repository\AvailabilityCalendarViewRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

final class AvailabilityFixtures extends Fixtures
{
    private const string HOTEL_FILE_PATH = 'tests/DataFixtures/hotels.yaml';
    private const int DEFAULT_CAPACITY = 2;

    public function __construct(
        private readonly AvailabilityCalendarViewRepository $availabilityRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $hotelsData = Yaml::parseFile(self::HOTEL_FILE_PATH)['hotels'];
        $today = new DateTimeImmutable();

        // Generate availability for the next 14 days
        for ($dayOffset = 0; $dayOffset < 14; $dayOffset++) {
            $currentDay = $today->modify(sprintf('+%d day', $dayOffset));

            foreach ($hotelsData as $hotel) {
                foreach ($hotel['rooms'] as $room) {
                    $this->availabilityRepository->save(
                        new AvailabilityCalendarView(
                            $hotel['id'],
                            $room['id'],
                            $currentDay,
                            'AVAILABLE',
                            $room['capacity'] ?? self::DEFAULT_CAPACITY
                        )
                    );
                }
            }
        }
    }
}