<?php

declare(strict_types=1);

namespace App\Loan\Application\Port;

use App\Loan\Domain\Client\Client;

interface NotifierInterface
{
    public function notify(Client $client, string $message): void;
}
