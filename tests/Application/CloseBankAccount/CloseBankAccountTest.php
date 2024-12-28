<?php

declare(strict_types=1);

namespace App\Tests\Application\CloseBankAccount;

use App\Api\Action\CloseBankAccountAction;
use App\Domain\BankAccount;
use App\Domain\BankAccountId;
use App\Domain\Command\OpenBankAccount;
use App\Domain\ValueObject\AccountStatus;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Tests\Application\ApplicationTestCase;
use EventSauce\EventSourcing\AggregateRootRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

#[CoversClass(CloseBankAccountAction::class)]
final class CloseBankAccountTest extends ApplicationTestCase
{
    private KernelBrowser $client;
    private BankAccountId $bankAccountId;

    public function setUp(): void
    {
        $this->client = self::createClient();

        self::getContainer()->get(AggregateRootRepository::class)->persist(
            BankAccount::openBankAccount(
                command: new OpenBankAccount(
                    bankAccountId: $this->bankAccountId = BankAccountId::create(),
                    accountHolderName: 'Test Name',
                    accountType: AccountType::SAVINGS,
                    currency: Currency::EUR
                )
            )
        );
    }

    public function testSetOverdraftLimit(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/close-bank-account/'.$this->bankAccountId->toString(),
        );

        $this->assertResponseIsSuccessful();

        $bankAccount = self::getContainer()->get(AggregateRootRepository::class)->retrieve($this->bankAccountId);

        $this->assertSame(AccountStatus::CLOSED, $bankAccount->getStatus());
    }
}
