<?php

declare(strict_types=1);

namespace App\Tests\Integration\Context\Campaign\Infrastructure\Write\Persistence\Doctrine\Repository;

use App\Context\Campaign\Domain\Write\Aggregate\Campaign;
use App\Context\Campaign\Domain\Write\Aggregate\ValueObject\Name;
use App\Context\Campaign\Infrastructure\Persistence\Doctrine\MySQL\Repository\DoctrineCampaignRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Exception\AggregateNotFoundException;
use App\Tests\Integration\RepositoryTestCase;

final class DoctrineCampaignRepositoryTest extends RepositoryTestCase
{
    public function test_it_saves_a_campaign(): void
    {
        $this->expectNotToPerformAssertions();

        $campaign = Campaign::create(
            Uuid::generate(),
            Name::fromString('Test Campaign'),
        );

        $this->repository()->save($campaign);
        $this->em()->flush();
        $this->em()->clear();
    }

    public function test_it_finds_a_campaign(): void
    {
        $expectedCampaign = $this->givenASavedCampaign();

        $foundCampaign = $this->repository()->find($expectedCampaign->id());

        $this->assertEquals($expectedCampaign->id(), $foundCampaign->id());
    }

    public function test_it_throws_exception_when_campaign_not_found(): void
    {
        $this->expectException(AggregateNotFoundException::class);

        $this->repository()->find(Uuid::generate());
    }

    private function givenASavedCampaign(): Campaign
    {
        $campaign = Campaign::create(
            Uuid::generate(),
            Name::fromString('Test Campaign'),
        );

        $this->repository()->save($campaign);
        $this->em()->flush();
        $this->em()->clear();

        return $campaign;
    }

    protected function purge(): void
    {
        $this->purgeTables('campaign');
    }

    protected function repository(): DoctrineCampaignRepository
    {
        return self::getContainer()->get('test.' . DoctrineCampaignRepository::class);
    }
}
