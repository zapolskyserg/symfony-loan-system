<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility\Modifier;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Eligibility\LoanModifierInterface;

final class PragueRandomDeclineModifier implements LoanModifierInterface
{
    public function modify(array $offers, Client $client): array
    {
        if ($client->getRegion()->getCode() !== 'PR') {
            return $offers;
        }

        // Рандомно відмовляємо у 50% випадків
        if (random_int(0, 1) === 0) {
            return []; // Повертаємо порожній масив, що означає відмову
        }

        return $offers;
    }
}
