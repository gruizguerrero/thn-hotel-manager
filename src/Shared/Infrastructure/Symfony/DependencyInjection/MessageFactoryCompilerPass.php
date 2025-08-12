<?php

namespace App\Shared\Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class MessageFactoryCompilerPass implements CompilerPassInterface
{
    private const string MESSAGE_TAG = 'message';
    private const string MESSAGE_FACTORY_SERVICE = 'message.message_factory';

    public function __construct()
    {
    }

    public function process(ContainerBuilder $container): void
    {
        $this->configureMessageFactoryMessagesMap($container);
    }

    private function configureMessageFactoryMessagesMap(ContainerBuilder $container): void
    {
        $messageFactory = $this->findMessageFactoryService($container);
        $messages = $this->findMessages($container);
        $messageFactory->addMethodCall('addMessagesToMap', [array_keys($messages)]);
    }

    private function findMessageFactoryService(ContainerBuilder $container): Definition
    {
        return $container->findDefinition(self::MESSAGE_FACTORY_SERVICE);
    }

    private function findMessages(ContainerBuilder $container): array
    {
        return $container->findTaggedServiceIds(self::MESSAGE_TAG);
    }
}
