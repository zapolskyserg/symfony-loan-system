<?php

declare(strict_types=1);

namespace App\Loan\Domain\Client\ValueObject;

use Symfony\Component\Uid\Uuid;

final readonly class ClientId
{
    private Uuid $uuid;

    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid ? Uuid::fromString($uuid) : Uuid::v4();
    }

    public function __toString(): string
    {
        return $this->uuid->toRfc4122();
    }
}
