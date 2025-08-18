<?php

declare(strict_types=1);

namespace App\Tests\Integration\Context\Hotel\Infrastructure\Read\Persistence\MySQL;

use App\Context\Hotel\Domain\Read\Entity\HotelView;
use App\Context\Hotel\Domain\Read\Repository\HotelViewRepository;
use App\Shared\Domain\Exception\ViewNotFoundException;
use App\Shared\Domain\Service\StringToBin;
use App\Shared\Domain\ValueObject\DateTimeInterface;
use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use App\Tests\Integration\ViewRepositoryTestCase;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final class MySQLHotelViewRepositoryTest extends ViewRepositoryTestCase
{
    private const string HOTEL_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const string HOTEL_NAME = 'Test Hotel';
    private const string HOTEL_COUNTRY = 'ES';
    private const string HOTEL_CITY = 'Madrid';
    private const int HOTEL_ROOMS = 5;

    protected function setUp(): void
    {
        parent::setUp();
        $this->insertHotelIntoDatabase();
    }

    public function test_it_finds_hotel_by_id(): void
    {
        $hotelView = $this->repository()->find(self::HOTEL_ID);

        $this->assertInstanceOf(HotelView::class, $hotelView);
        $this->assertEquals(self::HOTEL_ID, $hotelView->id());
        $this->assertEquals(self::HOTEL_NAME, $hotelView->name());
        $this->assertEquals(self::HOTEL_COUNTRY, $hotelView->country());
        $this->assertEquals(self::HOTEL_CITY, $hotelView->city());
        $this->assertEquals(self::HOTEL_ROOMS, $hotelView->numberOfRooms());
    }

    public function test_it_throws_exception_when_hotel_not_found(): void
    {
        $nonExistentId = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';

        $this->expectException(ViewNotFoundException::class);

        $this->repository()->find($nonExistentId);
    }

    protected function repository(): ViewRepository
    {
        return $this->getContainer()->get(HotelViewRepository::class);
    }

    private function insertHotelIntoDatabase(): void
    {
        $em = $this->getContainer()->get(EntityManagerInterface::class);

        $sql = "INSERT INTO hotels (id, name, country, city, number_of_rooms, created_at) 
                VALUES (:id, :name, :country, :city, :number_of_rooms, :created_at)";

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue('id', StringToBin::transformUuid(self::HOTEL_ID));
        $stmt->bindValue('name', self::HOTEL_NAME);
        $stmt->bindValue('country', self::HOTEL_COUNTRY);
        $stmt->bindValue('city', self::HOTEL_CITY);
        $stmt->bindValue('created_at', (new DateTimeImmutable())->format(DateTimeInterface::APP_FORMAT));
        $stmt->bindValue('number_of_rooms', self::HOTEL_ROOMS);

        $stmt->executeStatement();
    }

    protected function purge(): void
    {
        $this->purgeTables('hotels');
    }
}
