<?php

namespace App\Shared\Domain\ValueObject;

class StringValueObject
{
    public function __construct(private readonly string $value)
    {
    }

    public static function fromString($value): static
    {
        return new static($value);
    }

    public function equalsTo(StringValueObject $other): bool
    {
        return hash_equals($this->value, $other->value())
            && get_class($this) === get_class($other);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value();
    }
}