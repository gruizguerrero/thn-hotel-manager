<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\DataLoader\MySQL;

use App\Context\Hotel\Domain\Write\Aggregate\Hotel;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

final class HotelFixtures extends Fixtures
{
    private const string FILE_PATH = "tests/Fixtures/hotels.yaml";

    public function load(ObjectManager $manager): void
    {
        foreach (Yaml::parseFile(self::FILE_PATH)['hotels'] as $rawHotelFixture) {
            $hotel = Hotel::create(
                Uuid::fromString($rawHotelFixture['id']),
                Name::fromString($rawHotelFixture['name']),
                City::fromString($rawHotelFixture['city']),
                Country::fromString($rawHotelFixture['country'])
            );

            $manager->persist($hotel);
        }

        $manager->flush();
    }
}
