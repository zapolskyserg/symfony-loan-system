<?php

declare(strict_types=1);

namespace App\Loan\Domain\Client\ValueObject;

use InvalidArgumentException;

final readonly class Region
{
    // Список регіонів, які ми обслуговуємо
    private const ALLOWED_REGIONS = ['PR', 'BR', 'OS'];

    private string $code;

    public function __construct(string $code)
    {
        $this->ensureIsValidRegion($code);
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->code === $other->code;
    }

    private function ensureIsValidRegion(string $code): void
    {
        if (!in_array($code, self::ALLOWED_REGIONS, true)) {
            throw new InvalidArgumentException(
                sprintf('Region code <%s> is not allowed.', $code)
            );
        }
    }
}
