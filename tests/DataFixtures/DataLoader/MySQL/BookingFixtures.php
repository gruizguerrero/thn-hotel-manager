<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\DataLoader\MySQL;

use App\Context\Booking\Domain\Write\Aggregate\Booking;
use App\Context\Booking\Domain\Write\Entity\BookingRoom;
use App\Context\Booking\Domain\Write\Entity\BookingRooms;
use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomId;
use App\Shared\Domain\Service\ReflectionManager;
use App\Shared\Domain\ValueObject\DateTimeInterface;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class BookingFixtures extends Fixtures
{
    private const string FILE_PATH = "tests/DataFixtures/bookings.yaml";

    public function load(ObjectManager $manager): void
    {
        foreach (Yaml::parseFile(self::FILE_PATH)['bookings'] as $rawBookingFixture) {
            $bookingRooms = BookingRooms::createEmpty();
            foreach ($rawBookingFixture['room_ids'] as $roomId) {
                $bookingRoom = $this->buildWithReflectionManager(BookingRoom::class, [
                    'id' => UUID::generate(),
                    'roomId' => RoomId::fromString($roomId),
                ]);

                $bookingRooms->add($bookingRoom);
            }

            $booking = $this->buildWithReflectionManager(
                Booking::class,
                [
                    'id' => Uuid::fromString($rawBookingFixture['id']),
                    'hotelId' => Uuid::fromString($rawBookingFixture['hotel_id']),
                    'userId' => Uuid::fromString($rawBookingFixture['user_id']),
                    'checkInDate' => DateTimeImmutable::createFromFormat(DateTimeInterface::APP_FORMAT, $rawBookingFixture['check_in_date']),
                    'checkOutDate' => DateTimeImmutable::createFromFormat(DateTimeInterface::APP_FORMAT, $rawBookingFixture['check_out_date']),
                    'createdAt' => new DateTimeImmutable(),
                    'rooms' => $bookingRooms,
                ]
            );

            $manager->persist($booking);
        }

        $manager->flush();
    }

    private function buildWithReflectionManager(
        string $className,
        array $data
    ): object {
        $reflectionManager = ReflectionManager::create();

        return $reflectionManager->buildObject($className, $data);
    }
}
