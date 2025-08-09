<?php

declare(strict_types=1);

namespace App\Loan\Infrastructure\Persistence;

use App\Loan\Domain\Loan\LoanProduct;
use App\Loan\Domain\Loan\LoanProductRepositoryInterface;
use App\Loan\Domain\Shared\ValueObject\Money;

final class InMemoryLoanProductRepository implements LoanProductRepositoryInterface
{
    /** @var LoanProduct[] */
    private array $products = [];

    public function __construct()
    {
        $this->products = [
            new LoanProduct('Micro Loan', 500, Money::fromAmount(1000), 20.0),
            new LoanProduct('Standard Loan', 650, Money::fromAmount(5000), 15.0),
            new LoanProduct('Premium Loan', 750, Money::fromAmount(10000), 10.0),
        ];
    }

    public function findAll(): array
    {
        return $this->products;
    }
}
