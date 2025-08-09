<?php

namespace App\Shared\Domain;

use Doctrine\Common\Collections\ArrayCollection;

class Collection extends ArrayCollection
{
    public static function create(array $elements): static
    {
        return new static($elements);
    }

    public static function createEmpty(): static
    {
        return new static([]);
    }

    public function extract(): static
    {
        $elements = $this->toArray();
        $this->clear();

        return $this->createFrom($elements);
    }
}