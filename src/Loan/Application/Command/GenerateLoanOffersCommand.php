<?php

declare(strict_types=1);

namespace App\Loan\Application\Command;

final readonly class GenerateLoanOffersCommand
{
    public function __construct(
        public string $clientId
    ) {}
}
