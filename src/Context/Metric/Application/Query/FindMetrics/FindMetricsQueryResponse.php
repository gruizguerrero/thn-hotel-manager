<?php

declare(strict_types=1);

namespace App\Context\Metric\Application\Query\FindMetrics;

use App\Shared\Application\Bus\Query\Response;

final class FindMetricsQueryResponse implements Response
{
    public const string ID = 'id';
    public const string USERS = 'users';

    public function __construct(private readonly array $result)
    {

    }

    public function result(): array
    {
        return $this->result;
    }
}
