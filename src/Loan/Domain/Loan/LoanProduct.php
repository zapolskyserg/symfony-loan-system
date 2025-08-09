<?php

declare(strict_types=1);

namespace App\Loan\Domain\Loan;

use App\Loan\Domain\Shared\ValueObject\Money;
use DateTimeImmutable;

final class LoanProduct
{
    public function __construct(
        private readonly string $name,
        private readonly int $minScore,
        private readonly ?int $maxScore,
        private readonly Money $amount,
        private readonly float $rate,
        private readonly ?DateTimeImmutable $startDate = null,
        private readonly ?DateTimeImmutable $endDate = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    // --- ДОДАНО ВІДСУТНІ МЕТОДИ ---
    public function getMinScore(): int
    {
        return $this->minScore;
    }

    public function getMaxScore(): ?int
    {
        return $this->maxScore;
    }
    // -----------------------------

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }
}
