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

    public function id(): string
    {
        return $this->result[self::ID];
    }

    public function users(): string
    {
        return $this->result[self::USERS];
    }

    public function result(): array
    {
        return $this->result;
    }
}
