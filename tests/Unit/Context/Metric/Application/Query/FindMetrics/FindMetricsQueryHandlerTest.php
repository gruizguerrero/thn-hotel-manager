<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Metric\Application\Query\FindMetrics;

use App\Context\Metric\Application\Query\FindMetrics\FindMetricsQuery;
use App\Context\Metric\Application\Query\FindMetrics\FindMetricsQueryHandler;
use App\Context\Metric\Application\Query\FindMetrics\FindMetricsQueryResponse;
use App\Context\Metric\Application\Query\FindMetrics\FindMetricsQueryResponseConverter;
use App\Context\Metric\Domain\Read\Repository\HotelUniqueUsersViewRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class FindMetricsQueryHandlerTest extends TestCase
{
    private HotelUniqueUsersViewRepository|MockObject $hotelUniqueUsersViewRepository;
    private FindMetricsQueryResponseConverter $responseConverter;
    private FindMetricsQueryHandler $handler;
    private FindMetricsQuery $query;
    private array $repositoryResult;
    private FindMetricsQueryResponse $actualResponse;

    protected function setUp(): void
    {
        $this->hotelUniqueUsersViewRepository = $this->createMock(HotelUniqueUsersViewRepository::class);
        $this->responseConverter = new FindMetricsQueryResponseConverter();

        $this->handler = new FindMetricsQueryHandler(
            $this->hotelUniqueUsersViewRepository,
            $this->responseConverter
        );

        $this->query = FindMetricsQuery::create();
    }

    public function test_it_returns_all_hotel_unique_users_metrics(): void
    {
        $this->givenTheRepositoryReturnsHotelMetrics();

        $this->whenTheQueryIsHandled();

        $this->thenTheResponseShouldBeCorrect();
    }

    private function givenTheRepositoryReturnsHotelMetrics(): void
    {
        $this->repositoryResult = [
            ['hotel_id' => 'hotel-1', 'unique_users' => 5],
            ['hotel_id' => 'hotel-2', 'unique_users' => 10]
        ];

        $this->hotelUniqueUsersViewRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($this->repositoryResult);
    }

    private function whenTheQueryIsHandled(): void
    {
        $this->actualResponse = $this->handler->__invoke($this->query);
    }

    private function thenTheResponseShouldBeCorrect(): void
    {
        $this->assertInstanceOf(FindMetricsQueryResponse::class, $this->actualResponse);

        $data = $this->actualResponse->result();
        $this->assertIsArray($data);

        $hotels = $data;
        $this->assertCount(2, $hotels);

        $this->assertEquals('hotel-1', $hotels[0]['id']);
        $this->assertEquals(5, $hotels[0]['users']);

        $this->assertEquals('hotel-2', $hotels[1]['id']);
        $this->assertEquals(10, $hotels[1]['users']);
    }
}
