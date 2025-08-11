<?php

namespace App\Shared\UI\Controller;

use App\Shared\Application\Bus\Command\Command;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class ApiController extends AbstractController
{
    public function __construct(protected CommandBusInterface $commandBus)
    {
    }

    protected function dispatchCommand(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
