<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Command;

use App\Domain\BankAccountId;
use App\Domain\Command\OpenBankAccount;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OpenBankAccount::class)]
class OpenBankAccountTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: OpenBankAccount::class,
            actual: new OpenBankAccount(
                bankAccountId: BankAccountId::create(),
                accountHolderName: 'Test Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::EUR
            )
        );
    }
}
