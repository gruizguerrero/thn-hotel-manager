<?php

namespace App\Context\Campaign\Domain\Write\Event;

use App\Context\Campaign\Domain\Write\Aggregate\ValueObject\Name;
use App\Shared\Domain\Write\Event\DomainEvent;

final class CampaignCreated extends DomainEvent
{
    private const string NAME = 'name';

    public static function create(string $campaignId, string $name): self
    {
        return new self([
            self::AGGREGATE_ROOT_ID => $campaignId,
            self::NAME => $name,
        ]);
    }

    public function name(): Name
    {
        return $this->tryGet(self::NAME);
    }

    protected static function stringMessageName(): string
    {
        return 'campaign_manager.domain_event.campaign.created';
    }

    protected function version(): string
    {
        return '1.0';
    }
}
