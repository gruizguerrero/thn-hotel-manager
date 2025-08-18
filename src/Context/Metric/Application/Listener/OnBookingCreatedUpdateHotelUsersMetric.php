<?php

declare(strict_types=1);

namespace App\Context\Metric\Application\Listener;

use App\Context\Booking\Domain\Write\Event\BookingCreated;
use App\Context\Metric\Domain\Read\Entity\HotelUserDetailView;
use App\Context\Metric\Domain\Read\Repository\HotelUniqueUsersViewRepository;
use App\Context\Metric\Domain\Read\Repository\HotelUserDetailViewRepository;
use App\Shared\Application\Bus\Event\DomainEventListenerInterface;

final readonly class OnBookingCreatedUpdateHotelUsersMetric implements DomainEventListenerInterface
{
    public function __construct(
        private HotelUserDetailViewRepository $hotelUserDetailViewRepository,
        private HotelUniqueUsersViewRepository $hotelUniqueUsersViewRepository
    ) {
    }

    public function __invoke(BookingCreated $event): void
    {
        $hotelId = $event->hotelId()->value();
        $userId = $event->userId()->value();

        $existingDetail = $this->hotelUserDetailViewRepository->findByHotelIdAndUserId($hotelId, $userId);

        if (null === $existingDetail) {
            $this->hotelUserDetailViewRepository->save(new HotelUserDetailView($hotelId, $userId));
            $this->hotelUniqueUsersViewRepository->incrementUniqueUsers($hotelId);
        }
    }
}
