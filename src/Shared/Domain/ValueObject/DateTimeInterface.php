<?php

namespace App\Shared\Domain\ValueObject;

interface DateTimeInterface
{
    public const string APP_FORMAT = DATE_ATOM;
    public const string DB_FORMAT = 'Y-m-d H:i:s';
    public const string SHORT_DATE_FORMAT = 'Y-m-d';
    public const string READABLE_DATE_FORMAT = 'd.m.Y';
}