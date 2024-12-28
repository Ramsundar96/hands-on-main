<?php

declare(strict_types=1);

namespace App\Tests\Behavior\CloseBankAccount;

use App\Domain\BankAccount;
use App\Domain\Command\CloseBankAccount;
use App\Domain\Event\BankAccountClosed;
use App\Domain\Event\BankAccountOpened;
use App\Domain\Exception\CannotCloseBankAccountBecauseAccountIsNotActive;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class CannotCloseBankAccountBecauseAccountIsNotActiveTest extends BankAccountTestCase
{
    public function testCloseBankAccount_AccountIsClosed_ThrowsDomainException(): void
    {
        $this->given(
            new BankAccountOpened(
                bankAccountId: $this->aggregateRootId(),
                accountHolderName: 'Test Account Holder Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            ),
            new BankAccountClosed(
                bankAccountId: $this->aggregateRootId(),
            )
        )->when(
            new CloseBankAccount()
        )->expectToFail(
            new CannotCloseBankAccountBecauseAccountIsNotActive()
        );
    }
}
