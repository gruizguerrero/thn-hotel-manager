<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Hotel\Domain\Write\Aggregate\ValueObject;

use App\Context\Hotel\Domain\Write\Aggregate\ValueObject\Country;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

final class CountryTest extends TestCase
{
    public function test_it_throws_exception_when_an_only_spaces_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        Country::fromString('   ');
    }

    public function test_it_throws_exception_when_empty_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        Country::fromString('');
    }

    public function test_it_throws_exception_when_invalid_country_code_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        Country::fromString('A123');
    }

    public function test_it_returns_a_country_when_valid_country_code_is_given(): void
    {
        $countryCode = 'ES';
        $actualValue = Country::fromString($countryCode);
        $this->assertEquals($countryCode, $actualValue->value());
    }
}