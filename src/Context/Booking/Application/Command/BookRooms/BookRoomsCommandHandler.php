<?php

declare(strict_types=1);

namespace App\Context\Booking\Application\Command\BookRooms;

use App\Context\Booking\Domain\Write\Aggregate\Booking;
use App\Context\Booking\Domain\Write\Entity\BookingRoom;
use App\Context\Booking\Domain\Write\Entity\BookingRooms;
use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomId;
use App\Context\Booking\Domain\Write\Entity\ValueObject\RoomIds;
use App\Context\Booking\Domain\Write\Repository\BookingRepository;
use App\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\ValueObject\Uuid;

final readonly class BookRoomsCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BookingRepository $bookingRepository,
    ){
    }

    public function __invoke(BookRoomsCommand $command): void
    {
        $booking = Booking::bookRooms(
            $command->id(),
            $command->hotelId(),
            $command->userId(),
            $command->checkInDate(),
            $command->checkOutDate(),
            $this->buildBookingRooms($command->roomIds())
        );

        $this->bookingRepository->save($booking);
    }

    private function buildBookingRooms(
        RoomIds $roomIds,
    ): BookingRooms {
        $bookingRooms = BookingRooms::createEmpty();
        foreach ($roomIds as $roomId) {
            $bookingRooms->add(
                BookingRoom::create(
                    Uuid::generate(),
                    RoomId::fromString($roomId->value())
                )
            );
        }

        return $bookingRooms;
    }
}
