<?php

namespace App\Context\Hotel\UI\Controller;

use App\Context\Hotel\Application\Query\FindHotel\FindHotelQuery;
use App\Context\Hotel\Application\Query\FindHotelAvailability\FindHotelAvailabilityQuery;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\Domain\ValueObject\DateTimeInterface;
use App\Shared\Domain\Write\Exception\AggregateNotFoundException;
use App\Shared\UI\Controller\ApiController;
use App\Shared\UI\Response\ApiHttpResponse;
use App\Shared\UI\Response\HttpResponseCode;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;

final class GetHotelAvailabilityController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus,
        QueryBusInterface $queryBus,
        private readonly HotelAvailabilityResponseTransformer $transformer
    ){
        parent::__construct($commandBus, $eventBus, $queryBus);
    }

    public function __invoke(Request $request, string $hotelId): ApiHttpResponse
    {
        $fromParam = $request->query->get('from');
        $toParam   = $request->query->get('to');

        $tz = new \DateTimeZone('Europe/Madrid');
        $fromDate = DateTimeImmutable::createFromFormat('Y-m-d', $fromParam, $tz);
        $toDate   = DateTimeImmutable::createFromFormat('Y-m-d', $toParam,   $tz);

        try {
            $result = $this->ask(
                FindHotelAvailabilityQuery::create($hotelId, $fromDate, $toDate)
            );
        } catch (AggregateNotFoundException $e) {
            return new ApiHttpResponse(['error' => 'Hotel not found'], HttpResponseCode::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new ApiHttpResponse(['error' => 'Internal error'], HttpResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new ApiHttpResponse($this->transformer->transform($result), HttpResponseCode::HTTP_OK);
    }
}
