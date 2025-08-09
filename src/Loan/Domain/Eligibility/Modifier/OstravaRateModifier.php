<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility\Modifier;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Eligibility\LoanModifierInterface;
use App\Loan\Domain\Loan\LoanOffer;

final class OstravaRateModifier implements LoanModifierInterface
{
    public function modify(array $offers, Client $client): array
    {
        if ($client->getRegion()->getCode() !== 'OS') {
            return $offers;
        }

        $modifiedOffers = [];
        foreach ($offers as $offer) {
            $modifiedOffers[] = new LoanOffer(
                $offer->productName,
                $offer->amount,
                $offer->rate + 5.0 // Збільшуємо ставку на 5 процентних пунктів
            );
        }

        return $modifiedOffers;
    }
}
