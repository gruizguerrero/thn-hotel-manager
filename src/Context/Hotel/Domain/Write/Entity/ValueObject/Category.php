<?php

declare(strict_types=1);

namespace App\Context\Hotel\Domain\Write\Entity\ValueObject;

use App\Shared\Domain\Service\Assert;
use App\Shared\Domain\ValueObject\StringValueObject;

enum Category: string
{
    case STANDARD = 'standard';
    case DELUXE = 'deluxe';
    case SUITE = 'suite';
}
