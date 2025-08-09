<?php

declare(strict_types=1);

namespace App\Loan\Domain\Shared\ValueObject;

final readonly class Money
{
    // Ми зберігаємо гроші в центах, щоб уникнути проблем з float
    private int $amountInCents;

    public function __construct(int $amountInCents)
    {
        $this->amountInCents = $amountInCents;
    }

    /**
     * Створює об'єкт Money зі звичайної суми (наприклад, 1500.50)
     */
    public static function fromAmount(float $amount): self
    {
        return new self((int)($amount * 100));
    }

    public function getAmountInCents(): int
    {
        return $this->amountInCents;
    }
}
