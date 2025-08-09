<?php

declare(strict_types=1);

namespace App\Loan\Infrastructure\Persistence;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Client\ClientRepositoryInterface;
use App\Loan\Domain\Client\ValueObject\ClientId;
use App\Loan\Domain\Client\ValueObject\CreditHistoryStatus;
use App\Loan\Domain\Client\ValueObject\Region;
use App\Loan\Domain\Loan\ValueObject\Money;

final class InMemoryClientRepository implements ClientRepositoryInterface
{
    /** @var array<string, Client> */
    private static array $clients = [];

    public function __construct()
    {
        // Заповнюємо "базу даних" початковими даними, якщо вона порожня.
        if (empty(self::$clients)) {
            $this->seedData();
        }
    }

    public function find(ClientId $id): ?Client
    {
        return self::$clients[(string)$id] ?? null;
    }

    public function save(Client $client): void
    {
        self::$clients[(string)$client->getId()] = $client;
    }

    private function seedData(): void
    {
        $clientsData = [
            ['id' => 'd290f1ee-6c54-4b01-90e6-d701748f0851', 'name' => 'Petr Pavel', 'age' => 35, 'region' => 'PR', 'income' => 2500, 'score' => 780, 'history' => CreditHistoryStatus::GOOD, 'pin' => '123-45-0001', 'email' => 'petr.pavel@example.com', 'phone' => '+420111111111'],
            ['id' => 'd290f1ee-6c54-4b01-90e6-d701748f0852', 'name' => 'Anna Novakova', 'age' => 25, 'region' => 'BR', 'income' => 1800, 'score' => 680, 'history' => CreditHistoryStatus::NONE, 'pin' => '123-45-0002', 'email' => 'anna.novakova@example.com', 'phone' => '+420222222222'],
            ['id' => 'd290f1ee-6c54-4b01-90e6-d701748f0853', 'name' => 'Jan Dvorak', 'age' => 45, 'region' => 'OS', 'income' => 3000, 'score' => 800, 'history' => CreditHistoryStatus::GOOD, 'pin' => '123-45-0003', 'email' => 'jan.dvorak@example.com', 'phone' => '+420333333333'],
            ['id' => 'd290f1ee-6c54-4b01-90e6-d701748f0854', 'name' => 'Eva Cerna', 'age' => 50, 'region' => 'PR', 'income' => 1200, 'score' => 700, 'history' => CreditHistoryStatus::BAD, 'pin' => '123-45-0004', 'email' => 'eva.cerna@example.com', 'phone' => '+420444444444'],
            ['id' => 'd290f1ee-6c54-4b01-90e6-d701748f0855', 'name' => 'Tomas Maly', 'age' => 22, 'region' => 'BR', 'income' => 1100, 'score' => 550, 'history' => CreditHistoryStatus::NONE, 'pin' => '123-45-0005', 'email' => 'tomas.maly@example.com', 'phone' => '+420555555555'],
        ];

        foreach ($clientsData as $data) {
            $client = new Client(
                new ClientId($data['id']),
                $data['name'],
                $data['age'],
                new Region($data['region']),
                Money::fromAmount($data['income']),
                $data['score'],
                $data['history'],
                $data['pin'],
                $data['email'],
                $data['phone']
            );
            $this->save($client);
        }
    }
}
