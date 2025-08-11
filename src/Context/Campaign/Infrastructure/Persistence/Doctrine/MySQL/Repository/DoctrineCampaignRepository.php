<?php

declare(strict_types=1);

namespace App\Context\Campaign\Infrastructure\Persistence\Doctrine\MySQL\Repository;

use App\Context\Campaign\Domain\Write\Aggregate\Campaign;
use App\Context\Campaign\Domain\Write\Repository\CampaignRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineCampaignRepository extends AggregateRepository implements CampaignRepository
{
    public function save(Campaign $campaign): void
    {
        $this->saveAggregate($campaign);
    }

    public function find(Uuid $id): Campaign
    {
        return $this->doFind($id);
    }

    protected function entityClassName(): string
    {
        return Campaign::class;
    }
}