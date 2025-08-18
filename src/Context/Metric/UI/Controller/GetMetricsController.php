<?php

namespace App\Context\Metric\UI\Controller;

use App\Context\Metric\Application\Query\FindMetrics\FindMetricsQuery;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\UI\Controller\ApiController;
use App\Shared\UI\Response\ApiHttpResponse;
use App\Shared\UI\Response\HttpResponseCode;
use Symfony\Component\HttpFoundation\Request;

final class GetMetricsController extends ApiController
{
    public function __construct(
        CommandBusInterface $commandBus,
        EventBusInterface $eventBus,
        QueryBusInterface $queryBus,
        private readonly MetricsResponseTransformer $transformer
    ){
        parent::__construct($commandBus, $eventBus, $queryBus);
    }

    public function __invoke(Request $request): ApiHttpResponse
    {
        try {
            $queryResponse = $this->ask(FindMetricsQuery::create());
        } catch (\Throwable) {
            return new ApiHttpResponse([], HttpResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new ApiHttpResponse($this->transformer->transform($queryResponse));
    }
}
