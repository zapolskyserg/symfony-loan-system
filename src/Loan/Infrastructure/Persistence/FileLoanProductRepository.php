<?php

declare(strict_types=1);

namespace App\Loan\Infrastructure\Persistence;

use App\Loan\Domain\Loan\LoanProduct;
use App\Loan\Domain\Loan\LoanProductRepositoryInterface;
use App\Loan\Domain\Shared\ValueObject\Money;
use DateTimeImmutable;

final class FileLoanProductRepository implements LoanProductRepositoryInterface
{
    /** @var LoanProduct[]|null */
    private ?array $productsCache = null;

    public function __construct(private readonly string $filePath) {}

    public function findAll(): array
    {
        if ($this->productsCache !== null) {
            return $this->productsCache;
        }

        $json = file_get_contents($this->filePath);
        $productsData = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $this->productsCache = [];
        foreach ($productsData as $data) {
            $this->productsCache[] = new LoanProduct(
                $data['name'],
                $data['minScore'],
                $data['maxScore'],
                Money::fromAmount($data['amount']),
                $data['rate'],
                // Перетворюємо рядок на об'єкт дати, якщо він існує
                $data['startDate'] ? new DateTimeImmutable($data['startDate']) : null,
                $data['endDate'] ? new DateTimeImmutable($data['endDate']) : null
            );
        }

        return $this->productsCache;
    }
}
