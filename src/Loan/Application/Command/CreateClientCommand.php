<?php

declare(strict_types=1);

namespace App\Loan\Application\Command;

final readonly class CreateClientCommand
{
    public function __construct(
        public string $name,
        public int $age,
        public string $regionCode,
        public float $income,
        public int $score,
        public string $creditHistoryStatus,
        public string $pin,
        public ?string $email = null,
        public ?string $phone = null,
    ) {}
}
