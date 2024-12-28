<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\BankAccountId;
use App\Domain\Event\BankAccountOpened;
use App\Domain\ValueObject\AccountType;
use App\Domain\ValueObject\Currency;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BankAccountOpened::class)]
class BankAccountOpenedTest extends TestCase
{
    public function test__construct(): void
    {
        $this->assertInstanceOf(
            expected: BankAccountOpened::class,
            actual: new BankAccountOpened(
                bankAccountId: BankAccountId::create(),
                accountHolderName: 'Test Name',
                accountType: AccountType::SAVINGS,
                currency: Currency::USD
            )
        );
    }
}
