<?php

declare(strict_types=1);

namespace App\Loan\Domain\Loan;

interface LoanProductRepositoryInterface
{
    /**
     * @return LoanProduct[]
     */
    public function findAll(): array;
}
