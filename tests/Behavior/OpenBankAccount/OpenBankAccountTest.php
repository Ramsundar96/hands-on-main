<?php

declare(strict_types=1);

namespace App\Tests\Behavior\OpenBankAccount;

use App\Domain\BankAccount;
use App\Domain\Command\OpenBankAccount;
use App\Domain\Event\BankAccountOpened;
use App\Domain\ValueObject\AccountStatus;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class OpenBankAccountTest extends BankAccountTestCase
{
    public function testOpenAccount(): void
    {
        $this->when(
            new OpenBankAccount(
                bankAccountId: $this->aggregateRootId(),
                accountHolderName: 'Test Account Holder Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        )->then(
            new BankAccountOpened(
                bankAccountId: $this->aggregateRootId(),
                accountHolderName: 'Test Account Holder Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        );

        $bankAccount = $this->retrieveAggregateRoot($this->aggregateRootId());
        $this->assertSame('Test Account Holder Name', $bankAccount->getAccountHolderName());
        $this->assertSame(AccountType::SAVINGS, $bankAccount->getType());
        $this->assertSame(Currency::EUR, $bankAccount->getCurrency());
        $this->assertSame(0.0, $bankAccount->getBalance());
        $this->assertSame(0.0, $bankAccount->getOverdraftLimit());
        $this->assertSame(AccountStatus::ACTIVE, $bankAccount->getStatus());
    }
}
