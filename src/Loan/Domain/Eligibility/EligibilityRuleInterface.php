<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility;

use App\Loan\Domain\Client\Client;

interface EligibilityRuleInterface
{
    /**
     * Перевіряє, чи відповідає клієнт цьому правилу.
     * Якщо ні, має викинути виняток.
     */
    public function check(Client $client): void;
}
