<?php

namespace App\Context\Campaign\Application\Command\CreateCampaign;

use App\Shared\Application\Bus\Command\Command;

class CreateCampaignCommand extends Command
{
    private const string ID = 'id';
    private const string NAME = 'name';

    public static function create(string $campaignId, string $name): self
    {
        return new self([
            self::ID => $campaignId,
            self::NAME => $name
        ]);
    }

    protected static function stringMessageName(): string
    {
        return 'command.campaign.create';
    }

    protected function version(): string
    {
        return '1.0';
    }
}