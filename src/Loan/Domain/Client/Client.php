<?php

declare(strict_types=1);

namespace App\Loan\Domain\Client;

use App\Loan\Domain\Client\ValueObject\ClientId;
use App\Loan\Domain\Client\ValueObject\CreditHistoryStatus;
use App\Loan\Domain\Client\ValueObject\Region;
use App\Loan\Domain\Shared\ValueObject\Money;

final class Client
{
    public function __construct(
        private readonly ClientId $id,
        private readonly string $name,
        private readonly int $age,
        private readonly Region $region,
        private readonly Money $income,
        private readonly int $score,
        private readonly CreditHistoryStatus $creditHistory,
        private readonly string $pin,
        private readonly ?string $email = null,
        private readonly ?string $phone = null,
    ) {}

    public function getId(): ClientId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function getIncome(): Money
    {
        return $this->income;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getCreditHistory(): CreditHistoryStatus
    {
        return $this->creditHistory;
    }
    public function getPin(): string
    {
        return $this->pin;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
}
