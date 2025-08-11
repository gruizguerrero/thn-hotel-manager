<?php

declare(strict_types=1);

namespace App\Context\Campaign\Application\Command\CreateCampaign;

use App\Context\Campaign\Domain\Write\Aggregate\ValueObject\Name;
use App\Shared\Application\Bus\Command\Command;
use App\Shared\Domain\ValueObject\Uuid;

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

    public function id(): Uuid
    {
        return UUid::fromString($this->get(self::ID));
    }

    public function name(): Name
    {
        return Name::fromString($this->get(self::NAME));
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