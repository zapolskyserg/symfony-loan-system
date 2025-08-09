<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility\Rule;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Eligibility\EligibilityRuleInterface;
use App\Loan\Domain\Eligibility\Exception\EligibilityCheckException;

final class RegionRule implements EligibilityRuleInterface
{
    private const ALLOWED_REGIONS_FOR_LOAN = ['PR', 'BR', 'OS'];

    public function check(Client $client): void
    {
        $regionCode = $client->getRegion()->getCode();

        if (!in_array($regionCode, self::ALLOWED_REGIONS_FOR_LOAN, true)) {
            throw new EligibilityCheckException('Client region is not eligible for a loan.');
        }
    }
}
