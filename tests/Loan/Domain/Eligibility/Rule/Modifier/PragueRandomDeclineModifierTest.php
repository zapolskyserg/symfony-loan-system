<?php

namespace App\Tests\Loan\Domain\Eligibility\Modifier;

use App\Loan\Domain\Client\Client;
use App\Loan\Domain\Client\ValueObject\ClientId;
use App\Loan\Domain\Client\ValueObject\CreditHistoryStatus;
use App\Loan\Domain\Client\ValueObject\Region;
use App\Loan\Domain\Eligibility\Modifier\PragueRandomDeclineModifier;
use App\Loan\Domain\Loan\LoanOffer;
use App\Loan\Domain\Shared\RandomNumberGeneratorInterface;
use App\Loan\Domain\Shared\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class PragueRandomDeclineModifierTest extends TestCase
{
    public function testShouldDeclineOffersWhenRandomIsZero(): void
    {
        // 1. Створюємо "фальшивий" генератор, який ЗАВЖДИ повертає 0
        $stubGenerator = $this->createStub(RandomNumberGeneratorInterface::class);
        $stubGenerator->method('generate')->willReturn(0);

        $modifier = new PragueRandomDeclineModifier($stubGenerator);
        $client = $this->createClientFromRegion('PR');
        $offers = [new LoanOffer('Test', Money::fromAmount(1000), 10)];

        // 2. Викликаємо метод
        $result = $modifier->modify($offers, $client);

        // 3. Перевіряємо, що масив пропозицій порожній (відмова)
        $this->assertEmpty($result);
    }

    public function testShouldKeepOffersWhenRandomIsOne(): void
    {
        // 1. Створюємо "фальшивий" генератор, який ЗАВЖДИ повертає 1
        $stubGenerator = $this->createStub(RandomNumberGeneratorInterface::class);
        $stubGenerator->method('generate')->willReturn(1);

        $modifier = new PragueRandomDeclineModifier($stubGenerator);
        $client = $this->createClientFromRegion('PR');
        $offers = [new LoanOffer('Test', Money::fromAmount(1000), 10)];

        // 2. Викликаємо метод
        $result = $modifier->modify($offers, $client);

        // 3. Перевіряємо, що пропозиції залишились (не відмова)
        $this->assertCount(1, $result);
        $this->assertSame($offers, $result);
    }

    public function testShouldDoNothingForNonPragueClient(): void
    {
        // Створюємо реальний генератор, бо його не мають викликати
        $generator = $this->createMock(RandomNumberGeneratorInterface::class);
        $generator->expects($this->never())->method('generate'); // Очікуємо, що метод generate НЕ буде викликаний

        $modifier = new PragueRandomDeclineModifier($generator);
        $client = $this->createClientFromRegion('BR'); // Регіон не Прага
        $offers = [new LoanOffer('Test', Money::fromAmount(1000), 10)];

        $result = $modifier->modify($offers, $client);

        $this->assertSame($offers, $result);
    }

    private function createClientFromRegion(string $regionCode): Client
    {
        return new Client(
            new ClientId(),
            'Test',
            30,
            new Region($regionCode),
            Money::fromAmount(2000),
            600,
            CreditHistoryStatus::NONE,
            '123',
            'a@b.c',
            '123'
        );
    }
}
