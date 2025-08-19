<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Metric\Application\Listener;

use App\Context\Booking\Domain\Write\Event\BookingCreated;
use App\Context\Metric\Application\Listener\OnBookingCreatedUpdateHotelUsersMetric;
use App\Context\Metric\Domain\Read\Entity\HotelUserDetailView;
use App\Context\Metric\Domain\Read\Repository\HotelUserDetailViewRepository;
use App\Context\Metric\Domain\Read\Repository\HotelUniqueUsersViewRepository;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class OnBookingCreatedUpdateHotelUsersMetricTest extends TestCase
{
    private const string HOTEL_ID = '550e8400-e29b-41d4-a716-446655440000';
    private const string USER_ID = 'f47ac10b-58cc-4372-a567-0e02b2c3d479';
    private HotelUserDetailViewRepository|MockObject $hotelUserDetailViewRepository;
    private HotelUniqueUsersViewRepository|MockObject $hotelUniqueUsersViewRepository;
    private OnBookingCreatedUpdateHotelUsersMetric $listener;

    protected function setUp(): void
    {
        $this->hotelUserDetailViewRepository = $this->createMock(HotelUserDetailViewRepository::class);
        $this->hotelUniqueUsersViewRepository = $this->createMock(HotelUniqueUsersViewRepository::class);

        $this->listener = new OnBookingCreatedUpdateHotelUsersMetric(
            $this->hotelUserDetailViewRepository,
            $this->hotelUniqueUsersViewRepository
        );
    }

    public function test_it_saves_hotel_user_detail_and_increments_counter_for_new_user(): void
    {
        $event = $this->givenABookingCreatedEvent();
        $this->givenUserHasNotBookedThisHotelBefore();

        $this->thenHotelUserDetailShouldBeSaved();
        $this->thenUniqueUsersCounterShouldBeIncremented();

        $this->whenEventIsHandled($event);
    }

    public function test_it_does_not_update_metrics_for_existing_user(): void
    {
        $event = $this->givenABookingCreatedEvent();
        $this->givenUserHasAlreadyBookedThisHotel();

        $this->thenHotelUserDetailShouldNotBeSaved();
        $this->thenUniqueUsersCounterShouldNotBeIncremented();

        $this->whenEventIsHandled($event);
    }

    private function givenABookingCreatedEvent(): BookingCreated
    {
        return $this->createBookingCreatedEvent();
    }

    private function givenUserHasNotBookedThisHotelBefore(): void
    {
        $this->hotelUserDetailViewRepository
            ->expects($this->once())
            ->method('findByHotelIdAndUserId')
            ->with(self::HOTEL_ID, self::USER_ID)
            ->willReturn(null);
    }

    private function givenUserHasAlreadyBookedThisHotel(): void
    {
        $existingDetail = new HotelUserDetailView(self::HOTEL_ID, self::USER_ID);

        $this->hotelUserDetailViewRepository
            ->expects($this->once())
            ->method('findByHotelIdAndUserId')
            ->with(self::HOTEL_ID, self::USER_ID)
            ->willReturn($existingDetail);
    }

    private function thenHotelUserDetailShouldBeSaved(): void
    {
        $this->hotelUserDetailViewRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (HotelUserDetailView $view) {
                return $view->hotelId() === self::HOTEL_ID && $view->userId() === self::USER_ID;
            }));
    }

    private function thenHotelUserDetailShouldNotBeSaved(): void
    {
        $this->hotelUserDetailViewRepository
            ->expects($this->never())
            ->method('save');
    }

    private function thenUniqueUsersCounterShouldBeIncremented(): void
    {
        $this->hotelUniqueUsersViewRepository
            ->expects($this->once())
            ->method('incrementUniqueUsers')
            ->with(self::HOTEL_ID);
    }

    private function thenUniqueUsersCounterShouldNotBeIncremented(): void
    {
        $this->hotelUniqueUsersViewRepository
            ->expects($this->never())
            ->method('incrementUniqueUsers');
    }

    private function whenEventIsHandled(BookingCreated $event): void
    {
        $this->listener->__invoke($event);
    }

    private function createBookingCreatedEvent(): BookingCreated
    {
        $hotelId = Uuid::fromString(self::HOTEL_ID);
        $userId = Uuid::fromString(self::USER_ID);
        $checkInDate = new DateTimeImmutable('2025-09-01');
        $checkOutDate = new DateTimeImmutable('2025-09-05');
        $bookingId = Uuid::generate();

        return BookingCreated::create(
            $bookingId,
            $userId,
            $checkInDate,
            $checkOutDate,
            $hotelId
        );
    }
}
