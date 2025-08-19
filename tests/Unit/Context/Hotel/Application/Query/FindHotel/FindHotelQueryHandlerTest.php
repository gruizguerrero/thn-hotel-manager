<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Hotel\Application\Query\FindHotel;

use App\Context\Hotel\Application\Query\FindHotel\FindHotelQuery;
use App\Context\Hotel\Application\Query\FindHotel\FindHotelQueryHandler;
use App\Context\Hotel\Application\Query\FindHotel\FindHotelQueryResponse;
use App\Context\Hotel\Application\Query\FindHotel\FindHotelQueryResponseConverter;
use App\Context\Hotel\Domain\Read\Entity\HotelView;
use App\Context\Hotel\Domain\Read\Repository\HotelViewRepository;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class FindHotelQueryHandlerTest extends TestCase
{
    private const string HOTEL_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const string HOTEL_NAME = 'Test Hotel';
    private const string HOTEL_CITY = 'Madrid';
    private const string HOTEL_COUNTRY = 'ES';
    private const int HOTEL_ROOMS = 5;

    private HotelViewRepository|MockObject $hotelViewRepository;
    private FindHotelQueryHandler $handler;
    private FindHotelQuery $query;

    protected function setUp(): void
    {
        $responseConverter = new FindHotelQueryResponseConverter();
        $this->hotelViewRepository = $this->createMock(HotelViewRepository::class);

        $this->handler = new FindHotelQueryHandler(
            $responseConverter,
            $this->hotelViewRepository
        );
    }

    public function test_it_finds_hotel_by_id(): void
    {
        $this->givenAFindHotelQuery();
        $this->givenTheHotelExists();

        $response = $this->whenTheQueryIsHandled();

        $this->thenTheResponseShouldBeCorrect($response);
    }

    private function givenAFindHotelQuery(): void
    {
        $this->query = FindHotelQuery::fromHotelId(self::HOTEL_ID);
    }

    private function givenTheHotelExists(): void
    {
        $hotelView = new HotelView(
            self::HOTEL_ID,
            self::HOTEL_NAME,
            self::HOTEL_COUNTRY,
            self::HOTEL_CITY,
            self::HOTEL_ROOMS
        );

        $this->hotelViewRepository
            ->expects($this->once())
            ->method('find')
            ->with(self::HOTEL_ID)
            ->willReturn($hotelView);
    }

    private function whenTheQueryIsHandled(): FindHotelQueryResponse
    {
        return $this->handler->__invoke($this->query);
    }

    private function thenTheResponseShouldBeCorrect(FindHotelQueryResponse $response): void
    {
        $this->assertInstanceOf(FindHotelQueryResponse::class, $response);

        $result = $response->result();

        $this->assertEquals(self::HOTEL_ID, $result['id']);
        $this->assertEquals(self::HOTEL_NAME, $result['name']);
        $this->assertEquals(self::HOTEL_COUNTRY, $result['country']);
        $this->assertEquals(self::HOTEL_CITY, $result['city']);
        $this->assertEquals(self::HOTEL_ROOMS, $result['numberOfRooms']);
    }
}
