<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Loan\LoanOffer;

interface LoanModifierInterface
{
    /**
     * @param LoanOffer[] $offers
     * @return LoanOffer[]
     */
    public function modify(array $offers, Client $client): array;
}
