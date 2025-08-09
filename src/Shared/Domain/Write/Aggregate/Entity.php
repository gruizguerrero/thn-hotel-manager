<?php

namespace App\Shared\Domain\Write\Aggregate;

use App\Shared\Domain\ValueObject\Uuid;

abstract class Entity
{
    protected Uuid $id;

    protected function __construct(Uuid $id)
    {
        $this->setId($id);
    }

    private function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}