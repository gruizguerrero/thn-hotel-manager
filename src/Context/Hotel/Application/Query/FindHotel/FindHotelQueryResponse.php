<?php

declare(strict_types=1);

namespace App\Context\Hotel\Application\Query\FindHotel;

use App\Shared\Application\Bus\Query\Response;

final class FindHotelQueryResponse implements Response
{
    public const string ID = 'id';
    public const string NAME = 'name';
    public const string CITY = 'city';
    public const string COUNTRY = 'country';

    public function __construct(private readonly array $result)
    {

    }

    public function id(): string
    {
        return $this->result[self::ID];
    }

    public function name(): string
    {
        return $this->result[self::NAME];
    }

    public function city(): string
    {
        return $this->result[self::CITY];
    }

    public function country(): string
    {
        return $this->result[self::COUNTRY];
    }

    public function result(): array
    {
        return $this->result;
    }
}
