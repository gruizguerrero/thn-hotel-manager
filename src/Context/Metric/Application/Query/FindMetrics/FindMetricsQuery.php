<?php

declare(strict_types=1);

namespace App\Context\Metric\Application\Query\FindMetrics;

use App\Shared\Application\Bus\Query\Query;

final class FindMetricsQuery extends Query
{
    public static function create(): self
    {
        return new self([]);
    }

    protected static function stringMessageName(): string
    {
        return 'metric.query.metric.find';
    }

    protected function version(): string
    {
        return '1.0';
    }
}
