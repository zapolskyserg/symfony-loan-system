<?php

declare(strict_types=1);

namespace App\Loan\Infrastructure\Service;

use App\Loan\Domain\Shared\RandomNumberGeneratorInterface;

final class PhpRandomNumberGenerator implements RandomNumberGeneratorInterface
{
    public function generate(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
