<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseKernelTestCase extends KernelTestCase
{
    protected function tearDown(): void
    {
        //override tearDown in order to avoid shut down the kernel in each test
    }

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }
}
