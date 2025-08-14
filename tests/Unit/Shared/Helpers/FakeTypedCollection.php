<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Helpers;

use App\Shared\Domain\TypedCollection;

final class FakeTypedCollection extends TypedCollection
{
    protected function type(): string
    {
        return FakeCollectionElement::class;
    }
}
