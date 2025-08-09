<?php

declare(strict_types=1);

namespace App\Loan\Domain\Client\ValueObject;

enum CreditHistoryStatus: string
{
    case GOOD = 'good';
    case BAD = 'bad';
    case NONE = 'none';
}
