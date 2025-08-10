<?php

namespace App\Shared\Application\Bus\Command;

interface CommandBusInterface
{
    public function dispatch(Command $command): void;
}
