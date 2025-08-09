<?php

declare(strict_types=1);

namespace App\Loan\Infrastructure\Persistence;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Client\ClientRepositoryInterface;
use App\Loan\Domain\Client\ValueObject\ClientId;
use App\Loan\Domain\Client\ValueObject\CreditHistoryStatus;
use App\Loan\Domain\Client\ValueObject\Region;
use App\Loan\Domain\Shared\ValueObject\Money;

final class FileClientRepository implements ClientRepositoryInterface
{
    public function __construct(private readonly string $filePath) {}

    public function find(ClientId $id): ?Client
    {
        $clients = $this->loadData();
        $clientId = (string)$id;

        if (!isset($clients[$clientId])) {
            return null;
        }

        $data = $clients[$clientId];

        // "Гідратація": перетворюємо масив даних назад у доменний об'єкт
        return new Client(
            new ClientId($data['id']),
            $data['name'],
            $data['age'],
            new Region($data['region']),
            Money::fromAmount($data['income']),
            $data['score'],
            CreditHistoryStatus::from($data['history']),
            $data['pin'],
            $data['email'],
            $data['phone']
        );
    }

    public function save(Client $client): void
    {
        $clients = $this->loadData();
        $clientId = (string)$client->getId();

        // "Дегідратація": перетворюємо доменний об'єкт у простий масив для збереження
        $clients[$clientId] = [
            'id' => (string)$client->getId(),
            'name' => $client->getName(),
            'age' => $client->getAge(),
            'region' => $client->getRegion()->getCode(),
            'income' => $client->getIncome()->getAmountInCents() / 100,
            'score' => $client->getScore(),
            'history' => $client->getCreditHistory()->value,
            'pin' => $client->getPin(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
        ];

        $this->saveData($clients);
    }

    private function loadData(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        $json = file_get_contents($this->filePath);
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    private function saveData(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        file_put_contents($this->filePath, $json);
    }
}
