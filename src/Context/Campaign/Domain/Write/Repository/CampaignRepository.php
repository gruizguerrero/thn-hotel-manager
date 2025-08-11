<?php

declare(strict_types=1);

namespace App\Context\Campaign\Domain\Write\Repository;

use App\Context\Campaign\Domain\Write\Aggregate\Campaign;
use App\Shared\Domain\ValueObject\Uuid;

interface CampaignRepository
{
    public function save(Campaign $campaign): void;

    public function find(Uuid $id): Campaign;
}
