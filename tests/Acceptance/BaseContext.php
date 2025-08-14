<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class BaseContext implements Context
{
    protected KernelInterface $kernel;
    protected ?Application $application = null;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function iRunACommand(string $name, ?array $parameters = []): CommandTester
    {
        $command = $this->getCommand($name);

        return $this->executeCommand($command, $parameters);
    }

    private function getCommand(string $name): Command
    {
        $this->setupApplication();

        return $this->findCommand($name);
    }

    private function executeCommand(Command $command, array $parameters): CommandTester
    {
        $commandTester = new CommandTester($command);
        $commandTester->execute($this->getCommandParams($command, $parameters));

        return $commandTester;
    }

    private function setupApplication(): void
    {
        if ($this->application === null) {
            $this->application = new Application($this->kernel);
        }
    }

    private function findCommand(string $name): Command
    {
        try {
            return $this->application->get($name);
        } catch (CommandNotFoundException $exception) {
            throw new InvalidArgumentException(
                sprintf('Command with name "%s" does not exist', $name)
            );
        }
    }

    private function getCommandParams(Command $command, array $parameters): array
    {
        $default = ['command' => $command->getName()];

        return array_merge(
            $default,
            $parameters
        );
    }
}