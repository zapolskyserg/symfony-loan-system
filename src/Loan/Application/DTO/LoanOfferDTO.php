<?php

declare(strict_types=1);

namespace App\Loan\Application\DTO;

final readonly class LoanOfferDTO
{
    public function __construct(
        public string $productName,
        public float $amount,
        public float $rate,
    ) {}
}
