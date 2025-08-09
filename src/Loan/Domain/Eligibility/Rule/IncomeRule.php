<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility\Rule;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Eligibility\EligibilityRuleInterface;
use App\Loan\Domain\Eligibility\Exception\EligibilityCheckException;

final class IncomeRule implements EligibilityRuleInterface
{
    // $1000 в центах
    private const MIN_INCOME_IN_CENTS = 100000;

    public function check(Client $client): void
    {
        $incomeInCents = $client->getIncome()->getAmountInCents();

        if ($incomeInCents < self::MIN_INCOME_IN_CENTS) {
            throw new EligibilityCheckException('Client income is below the minimum requirement of $1000.');
        }
    }
}
