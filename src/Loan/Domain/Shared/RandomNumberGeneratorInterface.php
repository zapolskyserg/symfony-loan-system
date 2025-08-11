<?php

declare(strict_types=1);

namespace App\Loan\Domain\Shared;

interface RandomNumberGeneratorInterface
{
    public function generate(int $min, int $max): int;
}
