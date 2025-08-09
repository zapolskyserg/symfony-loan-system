<?php

declare(strict_types=1);

namespace App\Loan\Application\DTO;

final readonly class GenerateLoanOffersResult
{
    /**
     * @param LoanOfferDTO[] $offers
     */
    private function __construct(
        public bool $isEligible,
        public array $offers = [],
        public ?string $reason = null,
    ) {}

    /**
     * Створює успішний результат з набором пропозицій.
     * @param LoanOfferDTO[] $offers
     */
    public static function eligible(array $offers): self
    {
        return new self(true, $offers);
    }

    /**
     * Створює неуспішний результат з причиною відмови.
     */
    public static function ineligible(string $reason): self
    {
        return new self(false, [], $reason);
    }
}
