<?php

namespace App\Context\Campaign\Application\Command\CreateCampaign;

use App\Context\Campaign\Domain\Write\Aggregate\Campaign;
use App\Context\Campaign\Domain\Write\Repository\CampaignRepository;
use App\Shared\Application\Bus\Command\CommandHandlerInterface;

final readonly class CreateCampaignCommandHandler implements CommandHandlerInterface
{
    public function __construct(private CampaignRepository $campaignRepository)
    {
    }

    public function __invoke(CreateCampaignCommand $command): void
    {
        $campaign = Campaign::create($command->id(), $command->name());

        $this->campaignRepository->save($campaign);
    }
}
