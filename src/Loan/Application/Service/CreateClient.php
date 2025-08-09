<?php

declare(strict_types=1);

namespace App\Loan\Application\Service;

use App\Loan\Application\Command\CreateClientCommand;
use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Client\ClientRepositoryInterface;
use App\Loan\Domain\Client\ValueObject\ClientId;
use App\Loan\Domain\Client\ValueObject\CreditHistoryStatus;
use App\Loan\Domain\Client\ValueObject\Region;
use App\Loan\Domain\Shared\ValueObject\Money;

final class CreateClient
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository
    ) {}

    public function __invoke(CreateClientCommand $command): void
    {
        // 1. Створюємо об'єкти-значення з "сирих" даних DTO
        $id = new ClientId(); // Генеруємо новий унікальний ID
        $region = new Region($command->regionCode);
        $income = Money::fromAmount($command->income);
        $creditHistory = CreditHistoryStatus::from($command->creditHistoryStatus);

        // 2. Створюємо доменну сутність Client
        $client = new Client(
            $id,
            $command->name,
            $command->age,
            $region,
            $income,
            $command->score,
            $creditHistory,
            $command->pin,
            $command->email,
            $command->phone
        );

        // 3. Зберігаємо клієнта за допомогою репозиторію
        $this->clientRepository->save($client);
    }
}
