<?php

declare(strict_types=1);

namespace App\Tests\Behavior\SetOverdraftLimit;

use App\Domain\BankAccount;
use App\Domain\Command\SetOverdraftLimit;
use App\Domain\Event\BankAccountOpened;
use App\Domain\Event\OverdraftLimitSet;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use App\Tests\Behavior\BankAccountTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BankAccount::class)]
final class SetOverdraftLimitTest extends BankAccountTestCase
{
    public function testSetOverdraftLimit(): void
    {
        $this->given(
            new BankAccountOpened(
                bankAccountId: $this->aggregateRootId(),
                accountHolderName: 'Test Account Holder Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        )->when(
            new SetOverdraftLimit(
                overdraftLimit: 500
            )
        )->then(
            new OverdraftLimitSet(
                bankAccountId: $this->aggregateRootId(),
                newOverdraftLimit: 500.00,
                oldOverdraftLimit: 0
            )
        );

        $bankAccount = $this->retrieveAggregateRoot($this->aggregateRootId());
        $this->assertSame(500.00, $bankAccount->getOverdraftLimit());
    }
}
