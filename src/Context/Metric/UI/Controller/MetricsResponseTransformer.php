<?php

declare(strict_types=1);

namespace App\Context\Metric\UI\Controller;

use App\Context\Metric\Application\Query\FindMetrics\FindMetricsQueryResponse;
use App\Shared\Application\Bus\Query\Response;
use App\Shared\UI\Response\HttpResponseTransformer;

final class MetricsResponseTransformer extends HttpResponseTransformer
{
    protected function transformData(Response $queryResponse): array
    {
        if (!$queryResponse instanceof FindMetricsQueryResponse) {
            throw new \InvalidArgumentException('Expected ' . FindMetricsQueryResponse::class);
        }

        $response = [];
        foreach ($queryResponse->result() as $row) {
            $response[] = [
                FindMetricsQueryResponse::ID => $row[FindMetricsQueryResponse::ID],
                FindMetricsQueryResponse::USERS => $row[FindMetricsQueryResponse::USERS],
            ];
        }

        return $response;
    }

    protected function transformMetadata(Response $queryResponse): array
    {
        return [];
    }
}
