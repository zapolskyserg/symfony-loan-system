<?php

declare(strict_types=1);

namespace App\Loan\Domain\Eligibility\Modifier;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Client\ValueObject\CreditHistoryStatus;
use App\Loan\Domain\Eligibility\LoanModifierInterface;
use App\Loan\Domain\Loan\LoanOffer;
use App\Loan\Domain\Shared\ValueObject\Money;

final class CreditHistoryModifier implements LoanModifierInterface
{
    public function modify(array $offers, Client $client): array
    {
        $history = $client->getCreditHistory();
        $score = $client->getScore();

        if ($history === CreditHistoryStatus::NONE) {
            return $offers; // Для клієнтів без історії нічого не змінюємо
        }

        $modifiedOffers = [];
        foreach ($offers as $offer) {
            $amount = $offer->amount->getAmountInCents();
            $rate = $offer->rate;

            if ($history === CreditHistoryStatus::GOOD) {
                // Хороша історія: трохи збільшуємо суму
                $amount = (int)($amount * 1.05); // +5%
            }

            if ($history === CreditHistoryStatus::BAD) {
                // Погана історія: зменшуємо суму і збільшуємо ставку
                $amount = (int)($amount * 0.90); // -10%
                $rate += 0.5;
            }

            if ($score > 750 && $history === CreditHistoryStatus::BAD) {
                // Хороший рейтинг, але погана історія: ще більше збільшуємо ставку
                $rate += 0.5; // Разом +1.0%
            }

            $modifiedOffers[] = new LoanOffer(
                $offer->productName,
                new Money($amount),
                $rate
            );
        }

        return $modifiedOffers;
    }
}
