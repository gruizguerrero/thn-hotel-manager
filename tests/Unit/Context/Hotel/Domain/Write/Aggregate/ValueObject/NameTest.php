<?php

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Name;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    public function test_it_throws_exception_when_an_only_spaces_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        Name::fromString('   ');
    }

    public function test_it_throws_exception_when_empty_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        Name::fromString('');
    }

    public function test_it_returns_a_name_when_valid_string_is_given(): void
    {
        $stringName = 'NH Collection';
        $actualValue = Name::fromString($stringName);
        $this->assertEquals($stringName, $actualValue->value());
    }

    public function test_it_returns_a_name_when_valid_non_alphabetical_string_is_given(): void
    {
        $huang = '黄';
        $actualValue = Name::fromString($huang);
        $this->assertEquals($huang, $actualValue->value());
    }
}
