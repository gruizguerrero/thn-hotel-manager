<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Write\Aggregate\Entity;
use App\Shared\Domain\Write\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;

abstract class EntityRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    protected function doSearch(Uuid $id)
    {
        return $this->getRepository()->find($id);
    }

    protected function doFind(Uuid $id)
    {
        $entity = $this->doSearch($id);

        if (is_null($entity)) {
            throw EntityNotFoundException::forId($id);
        }

        return $entity;
    }

    protected function doRemove(Entity $entity): void
    {
        $this->entityManager->remove($entity);
    }

    protected function saveEntity(Entity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function flush(): void
    {
        $this->entityManager->flush();
    }

    private function getRepository(): DoctrineEntityRepository
    {
        return $this->entityManager->getRepository($this->entityClassName());
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    abstract protected function entityClassName(): string;
}
