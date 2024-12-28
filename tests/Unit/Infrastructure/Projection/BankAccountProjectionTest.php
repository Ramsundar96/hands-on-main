<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Projection;

use App\Infrastructure\Projection\BankAccountProjection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BankAccountProjection::class)]
class BankAccountProjectionTest extends TestCase
{
    public function testFromDbalResultRow(): void
    {
        $this->assertEquals(
            expected: new BankAccountProjection(
                bankAccountId: '9a1fe8f2-050d-41b4-8245-06b06f4ae2d2',
                accountHolderName: 'Test Name',
                balance: 300,
                accountType: 'savings',
                currency: 'EUR',
                overdraftLimit: 500
            ),
            actual: BankAccountProjection::fromDbalResultRow([
                'bank_account_id' => '9a1fe8f2-050d-41b4-8245-06b06f4ae2d2',
                'account_holder_name' => 'Test Name',
                'balance' => 300,
                'account_type' => 'savings',
                'currency_code' => 'EUR',
                'overdraft_limit' => 500,
            ])
        );
    }
}
