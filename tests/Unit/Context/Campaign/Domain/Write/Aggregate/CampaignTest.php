<?php

declare(strict_types=1);

namespace App\Tests\Unit\Context\Campaign\Domain\Write\Aggregate;

use App\Context\Campaign\Domain\Write\Aggregate\Campaign;
use App\Context\Campaign\Domain\Write\Aggregate\ValueObject\Name;
use App\Shared\Domain\Service\UuidGenerator;
use PHPUnit\Framework\TestCase;

final class CampaignTest extends TestCase
{
    public function test_campaign_is_created(): void
    {
        $id = UuidGenerator::generate();
        $name = Name::fromString('test');

        $campaign = Campaign::create($id, $name);

        $this->assertTrue($id->equalsTo($campaign->id()));
        $this->assertEquals($name->value(), $campaign->name()->value());
    }
}