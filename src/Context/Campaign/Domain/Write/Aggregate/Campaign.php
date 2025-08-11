<?php

declare(strict_types=1);

namespace App\Context\Campaign\Domain\Write\Aggregate;

use App\Context\Campaign\Domain\Write\Aggregate\ValueObject\Name;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;

class Campaign extends AggregateRoot
{
    private Name $name;

    public static function create(Uuid $id, Name $name): self
    {
        $campaign = new self($id);
        $campaign->name = $name;

        return $campaign;
    }

    public function name(): Name
    {
        return $this->name;
    }
}
