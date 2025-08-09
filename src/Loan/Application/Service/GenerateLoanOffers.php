<?php

declare(strict_types=1);

namespace App\Loan\Application\Service;

use App\Loan\Application\Command\GenerateLoanOffersCommand;
use App\Loan\Application\DTO\GenerateLoanOffersResult;
use App\Loan\Application\DTO\LoanOfferDTO;
use App\Loan\Domain\Client\ClientRepositoryInterface;
use App\Loan\Domain\Client\ValueObject\ClientId;
use App\Loan\Domain\Eligibility\Exception\EligibilityCheckException;
use App\Loan\Domain\Loan\LoanOffer;
use App\Loan\Domain\Loan\LoanProductRepositoryInterface;
use DateTimeImmutable;

final readonly class GenerateLoanOffers
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private LoanProductRepositoryInterface $loanProductRepository,
        private CheckEligibility $checkEligibility,
        private iterable $modifiers,
    ) {}

    public function __invoke(GenerateLoanOffersCommand $command): GenerateLoanOffersResult
    {
        $client = $this->clientRepository->find(new ClientId($command->clientId));
        if ($client === null) {
            return GenerateLoanOffersResult::ineligible('Client not found.');
        }

        // 1. Запускаємо "жорсткі" правила перевірки
        try {
            ($this->checkEligibility)($client);
        } catch (EligibilityCheckException $e) {
            return GenerateLoanOffersResult::ineligible($e->getMessage());
        }

        $allProducts = $this->loanProductRepository->findAll();
        $suitableProducts = [];
        $currentDate = new DateTimeImmutable();

        // 2. Фільтруємо продукти за рейтингом та датою доступності
        foreach ($allProducts as $product) {
            if ($client->getScore() < $product->getMinScore()) {
                continue;
            }
            if ($product->getMaxScore() !== null && $client->getScore() > $product->getMaxScore()) {
                continue;
            }
            if ($product->getStartDate() && $currentDate < $product->getStartDate()) {
                continue;
            }
            if ($product->getEndDate() && $currentDate > $product->getEndDate()) {
                continue;
            }
            $suitableProducts[] = $product;
        }

        if (empty($suitableProducts)) {
            return GenerateLoanOffersResult::ineligible('No suitable loan products found for the client score.');
        }

        // 3. Створюємо початкові пропозиції
        $offers = [];
        foreach ($suitableProducts as $product) {
            $offers[] = new LoanOffer(
                $product->getName(),
                $product->getAmount(),
                $product->getRate()
            );
        }

        // 4. Застосовуємо всі модифікатори послідовно
        foreach ($this->modifiers as $modifier) {
            $offers = $modifier->modify($offers, $client);
        }

        if (empty($offers)) {
            return GenerateLoanOffersResult::ineligible('Loan application was declined by a specific rule (e.g. Prague location).');
        }

        // 5. Формуємо фінальні DTO для відповіді
        $offerDTOs = [];
        foreach ($offers as $offer) {
            $offerDTOs[] = new LoanOfferDTO(
                productName: $offer->productName,
                amount: $offer->amount->getAmountInCents() / 100,
                rate: $offer->rate
            );
        }

        return GenerateLoanOffersResult::eligible($offerDTOs);
    }
}
