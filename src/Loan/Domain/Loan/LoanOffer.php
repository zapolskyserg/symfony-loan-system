<?php

declare(strict_types=1);

namespace App\Loan\Domain\Loan;

use App\Loan\Domain\Shared\ValueObject\Money;

// readonly робить об'єкт незмінним після створення
final readonly class LoanOffer
{
    public function __construct(
        public string $productName,
        public Money $amount,
        public float $rate,
    ) {}
}
