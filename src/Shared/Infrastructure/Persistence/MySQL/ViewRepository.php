<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\MySQL;

use Doctrine\ORM\EntityManagerInterface;

abstract class ViewRepository
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }
}
