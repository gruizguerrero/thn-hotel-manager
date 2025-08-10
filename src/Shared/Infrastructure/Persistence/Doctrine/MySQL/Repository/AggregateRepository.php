<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\AggregateRoot;
use App\Shared\Domain\Write\Exception\AggregateNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

abstract class AggregateRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function saveAggregate(AggregateRoot $aggregate): void
    {
        // $this->saveAggregateEvents();

        parent::saveEntity($aggregate);
    }

    protected function doFind(Uuid $id)
    {
        $entity = self::doSearch($id);

        if (null === $entity) {
            throw AggregateNotFoundException::forId($id);
        }

        return $entity;
    }

    public function removeAggregate(AggregateRoot $aggregateRoot): void
    {
        // $this->saveAggregateEvents($aggregateRoot);

        parent::doRemove($aggregateRoot);
    }

    /** ToDo. Implement event publish
    private function saveAggregateEvents()
    {

    }*/
}