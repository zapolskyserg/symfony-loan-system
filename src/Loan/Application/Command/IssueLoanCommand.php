<?php

declare(strict_types=1);

namespace App\Loan\Application\Command;

final readonly class IssueLoanCommand
{
    public function __construct(
        public string $clientId,
        public string $productName,
        public float $amount,
        public float $rate
    ) {}
}
