<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Helpers;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;

final class FakeAggregateRoot extends AggregateRoot
{
    public function __construct(Uuid $id, private readonly string $aField)
    {
        parent::__construct($id);
    }

    public static function random(): self
    {
        return new self(Uuid::generate(), 'aFieldValue');
    }

    public function aField(): string
    {
        return $this->aField;
    }

    public function addRecord(): void
    {
        $this->recordEvent(FakeDomainEvent::create($this));
    }
}