<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\Service\UuidGenerator;

class Uuid
{
    private string $value;

    private function __construct(string $value)
    {
        $string = self::addUuid4Dashes($value);
        Assert::uuid4($string);
        $this->value = $string;
    }

    public static function generate(): static
    {
        return new self(UuidGenerator::generate()->value());
    }

    public static function fromString(string $value): static
    {
        return new self($value);
    }

    public function equalsTo(Uuid $other): bool
    {
        return $this->value === $other->value()
            && get_class($this) === get_class($other);
    }

    public static function addUuid4Dashes(string $stringUuid): string
    {
        if (strpos($stringUuid, '-') > 0) {
            return $stringUuid;
        }

        return array_reduce(
            [8, 13, 18, 23],
            function (string $carry, int $position) {
                return substr_replace($carry, '-', $position, 0);
            },
            $stringUuid
        );
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
