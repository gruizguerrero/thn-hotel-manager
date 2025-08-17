<?php

declare(strict_types=1);

namespace App\Context\Booking\UI\Controller;

use App\Context\Booking\Application\Command\BookRooms\BookRoomsCommand;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\Domain\ValueObject\DateTimeInterface;
use App\Shared\UI\Controller\ApiController;
use App\Shared\UI\Response\ApiHttpResponse;
use App\Shared\UI\Response\HttpResponseCode;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;

final class PostBookingController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus,
        QueryBusInterface $queryBus
    ){
        parent::__construct($commandBus, $eventBus, $queryBus);
    }

    public function __invoke(Request $request): ApiHttpResponse
    {
        $data = json_decode($request->getContent(), true);

        $checkInDate = DateTimeImmutable::createFromFormat(DateTimeInterface::APP_FORMAT, $data['checkInDate']);
        $checkOutDate = DateTimeImmutable::createFromFormat(DateTimeInterface::APP_FORMAT, $data['checkOutDate']);

        try {
            $this->commandBus->dispatch(
                BookRoomsCommand::create(
                    $data['bookingId'],
                    $data['hotelId'],
                    $data['userId'],
                    $checkInDate,
                    $checkOutDate,
                    $data['roomIds'] ?? []
                )
            );
        } catch (\Throwable $exception) {
            return new ApiHttpResponse([], HttpResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new ApiHttpResponse([], HttpResponseCode::HTTP_CREATED);
    }
}
