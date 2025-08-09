<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility\Rule;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Eligibility\EligibilityRuleInterface;
use App\Loan\Domain\Eligibility\Exception\EligibilityCheckException;

final class AgeRule implements EligibilityRuleInterface
{
    private const MIN_AGE = 18;
    private const MAX_AGE = 60;

    public function check(Client $client): void
    {
        $age = $client->getAge();

        if ($age < self::MIN_AGE || $age > self::MAX_AGE) {
            throw new EligibilityCheckException('Client age is not within the allowed range (18-60).');
        }
    }
}
