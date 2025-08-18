<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Shared\Infrastructure\Persistence\MySQL\ViewRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class ViewRepositoryTestCase extends BaseKernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        parent::setUp();
        $this->purge();
    }

    abstract protected function purge(): void;

    protected function purgeTables(string ...$tables): void
    {
        $connection = $this->em()->getConnection();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $tableName) {
            $truncateSql = $connection->getDatabasePlatform()->getTruncateTableSQL($tableName);
            $connection->executeStatement($truncateSql);
        }
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1;');
    }

    protected abstract function repository(): ViewRepository;

    protected function em(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }
}
