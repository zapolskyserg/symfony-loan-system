<?php

namespace App\Tests\Loan\Domain\Eligibility\Rule;

// Переконайтеся, що всі ці use оператори присутні
use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Client\ValueObject\ClientId;
use App\Loan\Domain\Client\ValueObject\CreditHistoryStatus;
use App\Loan\Domain\Client\ValueObject\Region;
use App\Loan\Domain\Eligibility\Exception\EligibilityCheckException;
use App\Loan\Domain\Eligibility\Rule\AgeRule;
use App\Loan\Domain\Shared\ValueObject\Money;
use PHPUnit\Framework\TestCase; // <-- Дуже важливий рядок

class AgeRuleTest extends TestCase
{
    public function testCheckShouldPassForValidAge(): void
    {
        $client = $this->createClientWithAge(35);
        $rule = new AgeRule();

        $rule->check($client);

        $this->assertTrue(true, 'No exception should be thrown for a valid age.');
    }

    public function testCheckShouldThrowExceptionForUnderageClient(): void
    {
        $this->expectException(EligibilityCheckException::class);
        $this->expectExceptionMessage('Client age is not within the allowed range (18-60).');

        $client = $this->createClientWithAge(17);
        $rule = new AgeRule();

        $rule->check($client);
    }

    public function testCheckShouldThrowExceptionForOverageClient(): void
    {
        $this->expectException(EligibilityCheckException::class);
        $this->expectExceptionMessage('Client age is not within the allowed range (18-60).');

        $client = $this->createClientWithAge(61);
        $rule = new AgeRule();

        $rule->check($client);
    }

    private function createClientWithAge(int $age): Client
    {
        return new Client(
            new ClientId(),
            'Test Client',
            $age,
            new Region('PR'),
            Money::fromAmount(2000),
            600,
            CreditHistoryStatus::NONE,
            '123-45-6789',
            'test@example.com',
            '+123456789'
        );
    }
}
