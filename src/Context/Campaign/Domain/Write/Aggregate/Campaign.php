<?php

namespace App\Context\Campaign\Domain\Write\Aggregate;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;

class Campaign extends AggregateRoot
{
    private string $name;
    private string $lastName;

    public static function create(Uuid $id, string $name): self
    {
        $campaign = new self($id);
        $campaign->name = $name;

        return $campaign;
    }

    public function name(): string
    {
        return $this->name;
    }
}