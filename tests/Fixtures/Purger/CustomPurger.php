<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Purger;

use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CustomPurger implements ORMPurgerInterface
{
    private ?EntityManagerInterface $em;

    public function purge(): void
    {
        $connection = $this->em->getConnection();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        $connection->executeStatement($this->tables());
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function tables(): string
    {
        return <<<SQL
DELETE FROM hotels;
SQL;
    }

    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }
}