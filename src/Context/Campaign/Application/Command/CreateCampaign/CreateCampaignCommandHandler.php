<?php

namespace App\Context\Campaign\Application\Command\CreateCampaign;

use App\Shared\Application\Bus\Command\CommandHandlerInterface;

final class CreateCampaignCommandHandler implements CommandHandlerInterface
{
    public function __construct()
    {
    }

    public function __invoke(CreateCampaignCommand $command): void
    {
    }
}
