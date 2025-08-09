<?php

declare(strict_types=1);

namespace App\Loan\Domain\Client;

use App\Loan\Domain\Client\ValueObject\ClientId;

interface ClientRepositoryInterface
{
    /**
     * Знаходить клієнта за його унікальним ідентифікатором.
     * Повертає null, якщо клієнт не знайдений.
     */
    public function find(ClientId $id): ?Client;

    /**
     * Зберігає стан клієнта (новий або існуючий).
     */
    public function save(Client $client): void;
}
