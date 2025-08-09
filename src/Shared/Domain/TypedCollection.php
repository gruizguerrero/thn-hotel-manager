<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Service\Assert;

abstract class TypedCollection extends Collection
{
    public function __construct(array $elements)
    {
        Assert::allIsInstanceOf($elements, $this->type());
        parent::__construct($elements);
    }

    abstract protected function type(): string;
}