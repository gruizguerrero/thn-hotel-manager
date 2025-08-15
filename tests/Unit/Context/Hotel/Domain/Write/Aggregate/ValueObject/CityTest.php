<?php

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\City;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

final class CityTest extends TestCase
{
    public function test_it_throws_exception_when_an_only_spaces_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        City::fromString('   ');
    }

    public function test_it_throws_exception_when_empty_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        City::fromString('');
    }

    public function test_it_returns_a_name_when_valid_string_is_given(): void
    {
        $stringName = 'Madrid';
        $actualValue = City::fromString($stringName);
        $this->assertEquals($stringName, $actualValue->value());
    }

    public function test_it_returns_a_name_when_valid_non_alphabetical_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);

        $huang = '黄';
        $actualValue = City::fromString($huang);
        $this->assertEquals($huang, $actualValue->value());
    }
}
