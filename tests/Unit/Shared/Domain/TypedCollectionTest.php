<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Domain;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

final class TypedCollectionTest extends TestCase
{
    public function test_it_throws_exception_if_it_is_not_a_valid_type(): void
    {
        $this->expectException(AssertionFailedException::class);
        FakeTypedCollection::create([new \stdClass()]);
    }

    public function test_it_can_be_created(): void
    {
        $collection = FakeTypedCollection::create([new FakeCollectionElement(1)]);
        $this->assertCount(1, $collection->toArray());
    }
}
