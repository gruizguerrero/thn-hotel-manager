<?php

declare(strict_types=1);

namespace App\Shared\Domain\Message\Factory;

use App\Shared\Domain\Message\Message;

interface MessageFactory
{
    public function create(string $messageName, array $payload, array $metadata): Message;

    public function addMessagesToMap(array $messageClassNames): void;
}
