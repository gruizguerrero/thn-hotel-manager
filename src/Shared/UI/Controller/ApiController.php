<?php

namespace App\Shared\UI\Controller;

use App\Shared\Application\Bus\Command\Command;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Domain\Write\Event\DomainEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class ApiController extends AbstractController
{
    public function __construct(
        protected CommandBusInterface $commandBus,
        protected EventBusInterface $eventBus,
    ){
    }

    protected function dispatchCommand(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }

    protected function publishEvent(DomainEvent $event): void
    {
        $this->eventBus->publish($event);
    }
}
