<?php

namespace App\Shared\Application\Bus\Query;

interface QueryBusInterface
{
    public function ask(Query $query): ?Response;
}
