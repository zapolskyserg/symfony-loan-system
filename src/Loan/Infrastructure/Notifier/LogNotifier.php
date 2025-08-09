<?php

declare(strict_types=1);

namespace App\Loan\Infrastructure\Notifier;

use App\Loan\Application\Port\NotifierInterface;
use App\Loan\Domain\Client\Client;
use Psr\Log\LoggerInterface;

final readonly class LogNotifier implements NotifierInterface
{
    public function __construct(private LoggerInterface $logger) {}

    public function notify(Client $client, string $message): void
    {
        $this->logger->info(
            sprintf('Notification to client %s: %s', $client->getName(), $message)
        );
    }
}
