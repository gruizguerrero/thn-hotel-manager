<?php

namespace App\Tests\Unit\Context\Campaign\Domain\Write\Aggregate;

use App\Context\Campaign\Domain\Write\Aggregate\Campaign;
use App\Shared\Domain\Service\UuidGenerator;
use PHPUnit\Framework\TestCase;

final class CampaignTest extends TestCase
{
    public function test_campaign_is_created(): void
    {
        $id = UuidGenerator::generate();
        $name = 'Test Campaign';

        $campaign = Campaign::create($id, $name);

        $this->assertTrue($id->equalsTo($campaign->id()));
        $this->assertEquals($name, $campaign->name());
    }
}