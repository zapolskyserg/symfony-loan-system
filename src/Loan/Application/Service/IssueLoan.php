<?php

declare(strict_types=1);

namespace App\Loan\Application\Service;

use App\Loan\Application\Command\IssueLoanCommand;
use App\Loan\Application\Port\NotifierInterface;
use App\Loan\Domain\Client\ClientRepositoryInterface;
use App\Loan\Domain\Client\ValueObject\ClientId;
use DomainException;

final readonly class IssueLoan
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private NotifierInterface $notifier,
    ) {}

    public function __invoke(IssueLoanCommand $command): void
    {
        $client = $this->clientRepository->find(new ClientId($command->clientId));

        if ($client === null) {
            throw new DomainException('Cannot issue loan to a non-existent client.');
        }

        // Тут мала б бути логіка створення і збереження сутності "Виданий Кредит" (Loan)
        // в її власний репозиторій.
        // Для спрощення ми просто імітуємо цей процес.

        $message = sprintf(
            'Your loan "%s" for the amount of $%s at a rate of %s%% has been approved.',
            $command->productName,
            number_format($command->amount, 2),
            $command->rate
        );

        $this->notifier->notify($client, $message);
    }
}
