<?php

declare(strict_types=1);

namespace App\Tests\Application\OpenBankAccount;

use App\Domain\BankAccountId;
use App\Domain\ValueObject\AccountType;
use App\Infrastructure\Projection\Repository\BankAccountProjectionRepository;
use App\Tests\Application\ApplicationTestCase;
use EventSauce\EventSourcing\AggregateRootRepository;
use Ramsey\Uuid\Uuid;

final class OpenBankAccountTest extends ApplicationTestCase
{
    public function testOpenBankAccount(): void
    {
        $client = static::createClient();
        $client->request(
            method: 'POST',
            uri: '/open-bank-account',
            content: json_encode([
                'accountHolderName' => 'Test Account Holder',
                'type' => AccountType::SAVINGS->value,
                'currency' => 'EUR',
            ]),
        );

        $this->assertResponseIsSuccessful();

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('bankAccountId', $content);
        $this->assertTrue(Uuid::isValid($content['bankAccountId']));

        $bankAccountId = BankAccountId::fromString($content['bankAccountId']);

        $bankAccount = self::getContainer()->get(AggregateRootRepository::class)->retrieve($bankAccountId);

        $this->assertSame('Test Account Holder', $bankAccount->getAccountHolderName());
        $this->assertSame('savings', $bankAccount->getType()->value);
        $this->assertSame('EUR', $bankAccount->getCurrency()->value);

        $bankAccountProjection = self::getContainer()->get(BankAccountProjectionRepository::class)->__invoke($bankAccountId);

        $this->assertSame('Test Account Holder', $bankAccountProjection->accountHolderName);
        $this->assertSame('savings', $bankAccountProjection->accountType);
        $this->assertSame('EUR', $bankAccountProjection->currency);
    }
}
