<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Hotel\Application\Query\FindHotelAvailability;

use App\Context\Hotel\Application\Query\FindHotelAvailability\FindHotelAvailabilityQuery;
use App\Context\Hotel\Application\Query\FindHotelAvailability\FindHotelAvailabilityQueryHandler;
use App\Context\Hotel\Application\Query\FindHotelAvailability\FindHotelAvailabilityQueryResponse;
use App\Context\Hotel\Application\Query\FindHotelAvailability\FindHotelAvailabilityQueryResponseConverter;
use App\Context\Hotel\Domain\Read\Entity\AvailableRooms;
use App\Context\Hotel\Domain\Read\Entity\AvailableRoomView;
use App\Context\Hotel\Domain\Read\Entity\HotelView;
use App\Context\Hotel\Domain\Read\Repository\AvailabilityCalendarViewRepository;
use App\Context\Hotel\Domain\Read\Repository\HotelViewRepository;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class FindHotelAvailabilityQueryHandlerTest extends TestCase
{
    private const string HOTEL_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const string ROOM_ID = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
    private const string HOTEL_NAME = 'Test Hotel';
    private const string HOTEL_CITY = 'Madrid';
    private const string HOTEL_COUNTRY = 'ES';
    private const int ROOM_CAPACITY = 2;
    private FindHotelAvailabilityQueryResponseConverter $responseConverter;
    private AvailabilityCalendarViewRepository|MockObject $availabilityCalendarViewRepository;
    private HotelViewRepository|MockObject $hotelViewRepository;
    private FindHotelAvailabilityQueryHandler $handler;
    private FindHotelAvailabilityQuery $query;
    private HotelView $hotelView;
    private FindHotelAvailabilityQueryResponse $actualResponse;

    protected function setUp(): void
    {
        $this->responseConverter = new FindHotelAvailabilityQueryResponseConverter();
        $this->availabilityCalendarViewRepository = $this->createMock(AvailabilityCalendarViewRepository::class);
        $this->hotelViewRepository = $this->createMock(HotelViewRepository::class);

        $this->handler = new FindHotelAvailabilityQueryHandler(
            $this->responseConverter,
            $this->availabilityCalendarViewRepository,
            $this->hotelViewRepository
        );
    }

    public function test_it_finds_hotel_availability_for_date_range(): void
    {
        $this->givenAHotelAvailabilityQuery();
        $this->givenTheHotelExists();
        $this->givenThereAreAvailableRooms();

        $this->whenTheQueryIsHandled();

        $this->thenTheResponseShouldBeCorrect();
    }

    private function givenAHotelAvailabilityQuery(): void
    {
        $fromDate = new DateTimeImmutable('2025-09-01');
        $toDate = new DateTimeImmutable('2025-09-05');

        $this->query = FindHotelAvailabilityQuery::create(
            self::HOTEL_ID,
            $fromDate,
            $toDate
        );
    }

    private function givenTheHotelExists(): void
    {
        $this->hotelView = new HotelView(
            self::HOTEL_ID,
            self::HOTEL_NAME,
            self::HOTEL_COUNTRY,
            self::HOTEL_CITY,
            3
        );

        $this->hotelViewRepository
            ->expects($this->once())
            ->method('find')
            ->with(self::HOTEL_ID)
            ->willReturn($this->hotelView);
    }

    private function givenThereAreAvailableRooms(): void
    {
        $availableRoomView = new AvailableRoomView(
            self::ROOM_ID,
            self::ROOM_CAPACITY
        );

        $availableRooms = new AvailableRooms([$availableRoomView]);

        $this->availabilityCalendarViewRepository
            ->expects($this->once())
            ->method('findAvailability')
            ->with(
                self::HOTEL_ID,
                $this->query->fromDate(),
                $this->query->toDate()
            )
            ->willReturn($availableRooms);

        $this->availabilityResults = [
            [
                'room_id' => self::ROOM_ID,
                'capacity' => self::ROOM_CAPACITY
            ]
        ];
    }

    private function whenTheQueryIsHandled(): void
    {
        $this->actualResponse = $this->handler->__invoke($this->query);
    }

    private function thenTheResponseShouldBeCorrect(): void
    {
        $this->assertInstanceOf(FindHotelAvailabilityQueryResponse::class, $this->actualResponse);

        $this->assertEquals(self::HOTEL_ID, $this->actualResponse->id());
        $this->assertEquals(self::HOTEL_NAME, $this->actualResponse->name());

        $availableRooms = $this->actualResponse->availableRooms();
        $this->assertCount(1, $availableRooms);

        $this->assertEquals(self::ROOM_ID, $availableRooms[0]['roomId']);
        $this->assertEquals(self::ROOM_CAPACITY, $availableRooms[0]['capacity']);
    }
}
