<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility\Modifier;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Eligibility\LoanModifierInterface;
use App\Loan\Domain\Shared\RandomNumberGeneratorInterface; // Додаємо use

final class PragueRandomDeclineModifier implements LoanModifierInterface
{
    // Додаємо залежність через конструктор
    public function __construct(private readonly RandomNumberGeneratorInterface $randomNumberGenerator) {}

    public function modify(array $offers, Client $client): array
    {
        if ($client->getRegion()->getCode() !== 'PR') {
            return $offers;
        }

        // Використовуємо наш сервіс замість random_int()
        if ($this->randomNumberGenerator->generate(0, 1) === 0) {
            return [];
        }

        return $offers;
    }
}
