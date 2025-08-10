<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Service\Assert;

class MessageName extends StringValueObject
{
    public const string SEPARATOR = '.';

    private string $company;
    private string $boundedContext;
    private string $module;
    private string $type;
    private string $action;

    public function __construct(string $value)
    {
        $parsedMessage = $this->parseMessage($value);

        $this->company = $parsedMessage[0];
        $this->boundedContext = $parsedMessage[1];
        $this->type = $parsedMessage[2];
        $this->module = $parsedMessage[3];
        $this->action = $parsedMessage[4];

        parent::__construct($value);
    }

    private function parseMessage(string $value): array
    {
        $splittedMessage = explode(self::SEPARATOR, $value);

        Assert::isArray($splittedMessage, $this->genericFormatExceptionMessage($value));
        Assert::count($splittedMessage, 5, $this->genericFormatExceptionMessage($value));
        Assert::allRegex(
            $splittedMessage,
            '/^[a-z]+(?:_[a-z0-9]+)*$/',
            $this->invalidMessagePartMessage($value)
        );

        return $splittedMessage;
    }

    public function company(): string
    {
        return $this->company;
    }

    public function boundedContext(): string
    {
        return $this->boundedContext;
    }

    public function module(): string
    {
        return $this->module;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function action(): string
    {
        return $this->action;
    }

    private function genericFormatExceptionMessage(string $value): string
    {
        return sprintf(
            'The message with name <%s> is invalid. Supported format: common_platform.bc.type.module.action',
            $value
        );
    }

    private function invalidMessagePartMessage(string $value): string
    {
        return sprintf(
            'The message with name <%s> is invalid. All message parts should be lowercase letters',
            $value
        );
    }
}