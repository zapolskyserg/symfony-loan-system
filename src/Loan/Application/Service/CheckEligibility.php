<?php

declare(strict_types=1);

namespace App\Loan\Application\Service;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Eligibility\EligibilityRuleInterface;

final readonly class CheckEligibility
{
    /**
     * @param iterable<EligibilityRuleInterface> $rules
     */
    public function __construct(private iterable $rules) {}

    public function __invoke(Client $client): void
    {
        foreach ($this->rules as $rule) {
            $rule->check($client);
        }
    }
}
