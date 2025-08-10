<?php

namespace App\Tests\Unit\Shared\Domain\Message;


use App\Shared\Domain\Message\Message;

final class FakeMessage extends Message
{
    public const string A_KEY = 'aKey';
    public const string MESSAGE_NAME_WITHOUT_PREFIX = 'bc.fake_type.module.message';
    public const string VERSION = '1.1';

    public function aMethodWhichTryToAccessANotExistingKey()
    {
        return $this->get('anInvalidKey');
    }

    public static function valid($someScalar): self
    {
        return new self([self::A_KEY => $someScalar]);
    }

    public function aField()
    {
        return $this->get(self::A_KEY);
    }

    protected static function stringMessageName(): string
    {
        return self::MESSAGE_NAME_WITHOUT_PREFIX;
    }

    protected function version(): string
    {
        return self::VERSION;
    }
}